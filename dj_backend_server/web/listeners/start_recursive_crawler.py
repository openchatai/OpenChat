# listeners.py

import os
import requests
from bs4 import BeautifulSoup
from yourapp.signals import website_data_source_added, website_data_source_crawling_completed
from yourapp.models import WebsiteDataSource, CrawledPages
from django.core.files.storage import default_storage
from django.core.files.base import ContentFile
from django.utils.text import slugify
from uuid import uuid4
from django.dispatch import receiver

@receiver(website_data_source_added)
def start_recursive_crawler(sender, **kwargs):
    event = kwargs['event']

    if not isinstance(event, WebsiteDataSourceWasAdded):
        return

    # Get the WebsiteDataSource object
    data_source_id = event.get_website_data_source_id()
    data_source = WebsiteDataSource.objects.get(pk=data_source_id)
    chatbot_id = event.get_chatbot_id()
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


def get_normalized_content(html):
    # Remove inline script and style tags and their contents
    # Remove all HTML tags except for line break and paragraph tags
    # Replace line breaks and paragraphs with new lines
    # Remove extra whitespace and normalize new lines
    # Trim leading and trailing whitespace
    # Decode any HTML entities in the content
    # Return the normalized content


def get_crawled_page_title(html):
    # Use BeautifulSoup to parse the HTML and extract the title
    # Return the title or None if not found


def extract_links(html, root_url):
    # Use BeautifulSoup to parse the HTML and extract all anchor tags
    # Extract the URLs from the anchor tags
    # Normalize the URLs (e.g., remove query parameters, fragments)
    # Remove any URL that does not belong to the same root URL host
    # Return the list of extracted and filtered URLs


def crawl(data_source_id, url, crawled_urls, max_pages, chatbot_id):
    # Check if the maximum page limit has been reached
    # Check if the URL has already been crawled
    # Add the current URL to the crawled URLs list

    try:
        # Send an HTTP GET request to the URL
        response = requests.get(url)

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
