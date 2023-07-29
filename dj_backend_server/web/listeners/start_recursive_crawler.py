# listeners.py

import os
import requests
from bs4 import BeautifulSoup
from web.signals.website_data_source_was_added import website_data_source_added
from web.signals.website_data_source_crawling_was_completed import website_data_source_crawling_completed 
from web.models.crawled_pages import CrawledPages
from web.models.website_data_sources import WebsiteDataSource
from django.core.files.storage import default_storage
from django.core.files.base import ContentFile
from django.utils.text import slugify
from uuid import uuid4
from django.dispatch import receiver
from urllib.parse import urlparse, urlunparse
from web.enums.website_data_source_status_enum import WebsiteDataSourceStatusType
import logging

@website_data_source_added.connect
def start_recursive_crawler(sender, **kwargs):
    # Get the WebsiteDataSource object
    data_source_id = kwargs['data_source_id']
    chatbot_id = kwargs['bot_id']

    data_source = WebsiteDataSource.objects.get(pk=data_source_id)
    root_url = data_source.root_url

    if data_source.crawling_status == WebsiteDataSourceStatusType.COMPLETED:
        return

    try:
        # Initialize an empty list to store crawled URLs
        crawled_urls = []

        # Set the crawling status to "in progress"
        data_source.crawling_status = WebsiteDataSourceStatusType.IN_PROGRESS
        data_source.save()

        # Start recursive crawling from the root URL
        max_pages = 15
        crawl(data_source_id, root_url, crawled_urls, max_pages, chatbot_id)

        # Set the crawling status to "completed"
        data_source.crawling_status = WebsiteDataSourceStatusType.COMPLETED
        data_source.save()

        website_data_source_crawling_completed.send(
            sender=None,
            chatbot_id=chatbot_id,
            website_data_source_id=data_source_id
        )
    except Exception:
        data_source.crawling_status = WebsiteDataSourceStatusType.FAILED
        data_source.save()

    website_data_source_crawling_completed.send(
        sender=None,
        chatbot_id=chatbot_id,
        website_data_source_id=data_source_id
    )


def store_crawled_page_content_to_database(url, response, chatbot_id, data_source_id, html):
    # Save the HTML content to a local file
    file_name = str(uuid4()) + ".html"
    folder_name = os.path.join("website_data_sources", str(data_source_id))
    file_path = os.path.join(folder_name, file_name)
    file_content = ContentFile(html.encode("utf-8"))
    default_storage.save(file_path, file_content)

    # Extract the title of the page
    title = get_crawled_page_title(html)

    # Create a CrawledPages object and save it to the database
    page = CrawledPages.objects.create(
        url=url,
        status_code=response.status_code,
        chatbot_id=chatbot_id,
        title=title,
        website_data_source_id=data_source_id,
        content_file=file_path,
    )

def calculate_crawling_progress(crawled_pages, max_pages):
    if max_pages <= 0:
        return 0  # Avoid division by zero

    progress = (crawled_pages / max_pages) * 100
    # Cap the progress at 100%
    return min(progress, 100)

def update_crawling_progress(chatbot_id, data_source_id, progress):
    try:
        data_source = WebsiteDataSource.objects.get(pk=data_source_id)
        data_source.crawling_progress = progress
        data_source.save()
    except WebsiteDataSource.DoesNotExist:
        pass

def get_normalized_content(html):
    # Remove inline script and style tags and their contents
    soup = BeautifulSoup(html, 'html.parser')
    for tag in soup(['script', 'style']):
        tag.decompose()

    # Remove all HTML tags except for line break and paragraph tags
    for tag in soup.find_all(True):
        if tag.name not in ['br', 'p']:
            tag.unwrap()

    # Replace line breaks and paragraphs with new lines
    for br in soup.find_all('br'):
        br.replace_with("\n")
    for p in soup.find_all('p'):
        p.replace_with("\n" + p.get_text() + "\n")

    # Remove extra whitespace and normalize new lines
    normalized_content = soup.get_text().strip()
    normalized_content = "\n".join(line.strip() for line in normalized_content.splitlines())

    # Decode any HTML entities in the content
    normalized_content = html.unescape(normalized_content)

    # Return the normalized content
    return normalized_content

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