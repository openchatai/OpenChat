import os
import requests
import re
from bs4 import BeautifulSoup
from web.signals.website_data_source_crawling_was_completed import website_data_source_crawling_completed 
from web.models.crawled_pages import CrawledPages
from web.models.website_data_sources import WebsiteDataSource
from django.core.files.storage import default_storage
from django.core.files.base import ContentFile
from django.utils.text import slugify
from uuid import uuid4
from urllib.parse import urlparse, urlunparse
from web.enums.website_data_source_status_enum import WebsiteDataSourceStatusType
from web.listeners.ingest_website_data_source import handle_crawling_completed

import logging
import os
from dotenv import load_dotenv

load_dotenv()


def start_recursive_crawler(data_source_id, chatbot_id):
    data_source = WebsiteDataSource.objects.get(pk=data_source_id)
    root_url = data_source.root_url

    if data_source.crawling_status == WebsiteDataSourceStatusType.COMPLETED.value:
        return

    try:
        # Initialize an empty list to store crawled URLs
        crawled_urls = []

        # Set the crawling status to "in progress"
        data_source.crawling_status = WebsiteDataSourceStatusType.IN_PROGRESS.value
        data_source.save()

        # Start recursive crawling from the root URL
        max_pages = int(os.environ.get('MAX_PAGES_CRAWL', 15))
        crawl(data_source_id, root_url, crawled_urls, max_pages, chatbot_id)

        handle_crawling_completed(chatbot_id=chatbot_id, website_data_source_id=data_source_id)        
    except Exception:
        data_source.crawling_status = WebsiteDataSourceStatusType.FAILED.value
        data_source.save()

# the file will be stored in the website_data_sources/<data_source_id>/ directory.
def store_crawled_page_content_to_database(url, response, chatbot_id, data_source_id, html):
    html = get_normalized_content(html)
    
    # Save the HTML content to a local file in /tmp directory
    file_name = str(uuid4()) + ".txt"
    folder_name = os.path.join("website_data_sources", str(data_source_id))
    file_path = os.path.join(folder_name, file_name)
    file_content = ContentFile(html.encode("utf-8"))
    default_storage.save(file_path, file_content)

    # Extract the title of the page
    title = get_crawled_page_title(html)

    # Create a CrawledPages object and save it to the database
    try:
        CrawledPages.objects.create(
            url=url,
            status_code=response.status_code,
            chatbot_id=chatbot_id,
            title=title,  
            website_data_source_id=data_source_id,
            content_file=file_path,
        )
    except Exception as e:
        print("Error creating CrawledPages object: ", e)


def calculate_crawling_progress(crawled_pages, max_pages):
    if max_pages <= 0:
        return 0  # Avoid division by zero

    progress = (crawled_pages / max_pages) * 100
    progress = round(progress, 2)
    return min(progress, 100)

def update_crawling_progress(chatbot_id, data_source_id, progress):
    try:
        data_source = WebsiteDataSource.objects.get(pk=data_source_id)
        data_source.crawling_progress = progress
        data_source.save()
    except WebsiteDataSource.DoesNotExist:
        pass


# This method needs to be revisited and has to be written in a more sophisticated manner. 
def get_normalized_content(html):
    # Define a list of tags and classes to exclude
    exclude_elements = ['script', 'style']
    exclude_classes = ['skip', 'menu', 'dropdown']

    soup = BeautifulSoup(html, features="lxml")

    # Function to recursively remove unwanted elements
    def clean_elements(element):
        for child in element.find_all(recursive=False):
            if child.name in exclude_elements or any(cls in child.get('class', []) for cls in exclude_classes):
                # Replace excluded elements with whitespace
                child.replace_with(" ")
            else:
                clean_elements(child)

    # Start cleaning from the root of the document
    clean_elements(soup)

    # Get the text content from the cleaned HTML
    text = soup.get_text()

    # Remove extra whitespace between words
    text = re.sub(r'\s+', ' ', text)

    # Trim whitespace
    text = text.strip()
    return text
    

def get_crawled_page_title(html):
    # Use BeautifulSoup to parse the HTML and extract the title
    soup = BeautifulSoup(html, 'html.parser')
    title_element = soup.find('title')

    # Return the title or None if not found
    return title_element.get_text() if title_element else None


def extract_links(html, root_url):
    # Use BeautifulSoup to parse the HTML and extract all anchor tags
    soup = BeautifulSoup(html, 'html.parser')
    anchor_tags = soup.find_all('a')

    # Extract the URLs from the anchor tags
    extracted_urls = []
    for tag in anchor_tags:
        url = tag.get('href')
        if url and url.strip():
            extracted_urls.append(url.strip())

    # Normalize the URLs (e.g., remove query parameters, fragments)
    normalized_urls = []
    for url in extracted_urls:
        parsed_url = urlparse(url)
        normalized_url = urlunparse((parsed_url.scheme, parsed_url.netloc, parsed_url.path, '', '', ''))
        normalized_urls.append(normalized_url)

    # Remove any URL that does not belong to the same root URL host
    root_url_parts = urlparse(root_url)
    filtered_urls = [url for url in normalized_urls if url.startswith(root_url_parts.scheme + '://' + root_url_parts.netloc)]

    # Return the list of extracted and filtered URLs
    return filtered_urls


def crawl(data_source_id, url, crawled_urls, max_pages, chatbot_id):
    # Check if the maximum page limit has been reached
    if len(crawled_urls) >= max_pages:
        return

    # Check if the URL has already been crawled
    if url in crawled_urls:
        return

    # Add the current URL to the crawled URLs list
    crawled_urls.append(url)

    try:
        # Send an HTTP GET request to the URL
        response = requests.get(url)
        response.raise_for_status()  # Raise an exception for bad responses (e.g., 404, 500)

        # Retrieve the HTML content of the page
        html = response.text

        # Store the crawled page content in the database
        store_crawled_page_content_to_database(url, response, chatbot_id, data_source_id, html)

        # Extract all the links from the HTML content
        links = extract_links(html, url)

        # Recursively crawl each extracted link
        for link in links:
            crawl(data_source_id, link, crawled_urls, max_pages, chatbot_id)

            # Update crawling progress
            progress = calculate_crawling_progress(len(crawled_urls), max_pages)
            update_crawling_progress(chatbot_id, data_source_id, progress)
    except requests.exceptions.RequestException:
        pass
    except Exception as e:
        # Handle other exceptions (e.g., invalid HTML, network issues) and continue crawling
        logging.exception(f"An unexpected error occurred while crawling URL: {url}")
        pass