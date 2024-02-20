import os
import requests
import re
import logging.config
import json
from dotenv import load_dotenv
from uuid import uuid4
from urllib.parse import urlparse, urlunparse
from bs4 import BeautifulSoup
from web.signals.website_data_source_crawling_was_completed import (
    website_data_source_crawling_completed,
)
from web.models.crawled_pages import CrawledPages
from web.models.website_data_sources import WebsiteDataSource
from web.enums.website_data_source_status_enum import WebsiteDataSourceStatusType
from web.listeners.ingest_website_data_source import handle_crawling_completed
from api.utils.init_vector_store import ensure_vector_database_exists
from django.core.files.storage import default_storage
from django.core.files.base import ContentFile
from django.utils.text import slugify
from django.conf import settings


load_dotenv()
logging.config.dictConfig(settings.LOGGING)
logger = logging.getLogger(__name__)


def start_recursive_crawler(data_source_id, chatbot_id):
    """
    This function starts a recursive crawler on a given data source. If the data source's crawling status is already
    completed, the function will return immediately. Otherwise, it will set the status to "in progress", start the
    crawling process, and handle the completion of the crawling. If any exception occurs during the process, the
    crawling status will be set to "failed".

    Args:
        data_source_id (int): The ID of the data source to be crawled. This should be a primary key of a WebsiteDataSource object.
        chatbot_id (int): The ID of the chatbot initiating the crawl. This is used when handling the completion of the crawling.

    Raises:
        Exception: If any error occurs during the crawling process, the function will catch the exception, set the
        crawling status to "failed", and re-raise the exception.
    """
    # Ensure vector database exists before starting the crawl

    ensure_vector_database_exists(str(chatbot_id))
    logging.debug("Starting recursive crawler")
    data_source = WebsiteDataSource.objects.get(pk=data_source_id)
    root_url = data_source.root_url

    if data_source.crawling_status == WebsiteDataSourceStatusType.COMPLETED.value:
        return

    try:
        # Initialize an empty list to store crawled URLs
        crawled_urls = []
        logging.debug("Starting recursive crawler")
        # Set the crawling status to "in progress"
        data_source.crawling_status = WebsiteDataSourceStatusType.IN_PROGRESS.value
        data_source.save()

        # Start recursive crawling from the root URL
        max_pages = int(os.environ.get("MAX_PAGES_CRAWL", 15))
        crawl(data_source_id, root_url, crawled_urls, max_pages, chatbot_id)
        handle_crawling_completed(
            chatbot_id=chatbot_id, website_data_source_id=data_source_id
        )
    except Exception:
        data_source.crawling_status = WebsiteDataSourceStatusType.FAILED.value
        data_source.save()


def store_crawled_page_content_to_database(
    url, response, chatbot_id, data_source_id, html
):
    """
    This function stores the content of a crawled web page to the database. It first normalizes the HTML content,
    saves it to a local file in the /website_data_sources directory, extracts the title of the page, and then creates
    a CrawledPages object and saves it to the database. If any error occurs during the creation of the
    CrawledPages object, the function will print the error message.

    Args:
        url (str): The URL of the web page that has been crawled.
        response (Response): The response object returned by the request to the URL.
        chatbot_id (int): The ID of the chatbot that initiated the crawl.
        data_source_id (int): The ID of the data source from which the web page was crawled.
        html (str): The HTML content of the web page.

    Raises:
        Exception: If any error occurs during the creation of the CrawledPages object, the function will catch the
        exception and print the error message.
    """
    # Extract the title of the page
    title = get_crawled_page_title(html)
    html = get_normalized_content(html)

    # Save the HTML content to a local file in /website_data_sources directory
    file_name = str(uuid4()) + ".txt"
    logging.debug(f"JSON FILE: {file_name}")
    folder_name = os.path.join("website_data_sources", str(data_source_id))
    file_path = os.path.join(folder_name, file_name)
    file_content = ContentFile(html.encode("utf-8"))
    default_storage.save(file_path, file_content)

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
    """
    This function calculates the progress of the crawling process. It divides the number of crawled pages by the maximum
    number of pages that should be crawled, multiplies the result by 100 to get a percentage, and then rounds the result
    to two decimal places. If the calculated progress exceeds 100, it is capped at 100. If the maximum number of pages
    is zero or less, the function returns 0 to avoid division by zero.

    Args:
        crawled_pages (int): The number of pages that have been crawled.
        max_pages (int): The maximum number of pages that should be crawled.

    Returns:
        float: The progress of the crawling process, as a percentage rounded to two decimal places.
    """
    if max_pages <= 0:
        return 0  # Avoid division by zero

    progress = (crawled_pages / max_pages) * 100
    progress = round(progress, 2)
    return min(progress, 100)


def update_crawling_progress(chatbot_id, data_source_id, progress):
    """
    This function updates the crawling progress of a specific data source. It retrieves the data source object from
    the database using the provided ID, sets its crawling progress to the provided value, and then saves the changes
    to the database. If no data source with the provided ID exists, the function does nothing.

    Args:
        chatbot_id (int): The ID of the chatbot that initiated the crawl. Currently not used in this function but could be useful for future enhancements.
        data_source_id (int): The ID of the data source whose crawling progress should be updated.
        progress (float): The new crawling progress, as a percentage.

    Raises:
        WebsiteDataSource.DoesNotExist: If no data source with the provided ID exists, the function will catch this exception and do nothing.
    """
    try:
        data_source = WebsiteDataSource.objects.get(pk=data_source_id)
        data_source.crawling_progress = progress
        data_source.save()
    except WebsiteDataSource.DoesNotExist:
        pass


def get_normalized_content(html):
    """
    This function normalizes the HTML content of a web page. It removes unwanted elements (such as 'script' and 'style' tags,
    and elements with 'skip', 'menu', or 'dropdown' classes), extracts the text content from the cleaned HTML, removes extra
    whitespace between words, and trims leading and trailing whitespace. The function uses BeautifulSoup to parse and manipulate
    the HTML, and a recursive function to clean unwanted elements.

    Args:
        html (str): The HTML content of the web page to be normalized.

    Returns:
        str: The normalized text content of the web page.
    """
    # Define a list of tags and classes to exclude
    exclude_elements = ["script", "style"]
    exclude_classes = ["skip", "menu", "dropdown"]

    soup = BeautifulSoup(html, features="lxml")

    # Function to recursively remove unwanted elements
    def clean_elements(element):
        """
        This function recursively removes unwanted elements from a BeautifulSoup element. It iterates over the direct children
        of the provided element. If a child element's tag name is in the 'exclude_elements' list or any of its classes are in the
        'exclude_classes' list, the child element is replaced with a whitespace. Otherwise, the function is called recursively on
        the child element.

        Args:
            element (bs4.element.Tag): The BeautifulSoup element to clean. This should be a tag object that potentially contains
            other tag objects (children).
        """
        for child in element.find_all(recursive=False):
            if child.name in exclude_elements or any(
                cls in child.get("class", []) for cls in exclude_classes
            ):
                # Replace excluded elements with whitespace
                child.replace_with(" ")
            else:
                clean_elements(child)

    # Start cleaning from the root of the document
    clean_elements(soup)

    # Get the text content from the cleaned HTML
    text = soup.get_text()

    # Remove extra whitespace between words
    text = re.sub(r"\s+", " ", text)

    # Trim whitespace
    text = text.strip()
    return text


def get_crawled_page_title(html):
    """
    This function extracts the title of a web page from its HTML content. It uses BeautifulSoup to parse the HTML and find
    the 'title' tag. If a 'title' tag is found, the function returns its text content. If no 'title' tag is found, or if the
    'title' tag is empty, the function attempts to extract a title using alternative methods (e.g., looking for h1 tags or
    meta tags with property="og:title"). If all attempts fail, it returns a default placeholder title.

    Args:
        html (str): The HTML content of the web page from which to extract the title.

    Returns:
        str: The title of the web page, or a default placeholder if no suitable title is found.
    """
    soup = BeautifulSoup(html, "html.parser")
    title_element = soup.find("title")
    if title_element and title_element.get_text().strip():
        return title_element.get_text().strip()

    # Attempt to find a title using h1 tags
    h1_element = soup.find("h1")
    if h1_element and h1_element.get_text().strip():
        return h1_element.get_text().strip()

    # Attempt to find a title using meta tags (e.g., OpenGraph)
    meta_title = soup.find("meta", property="og:title")
    if meta_title and meta_title.get("content").strip():
        return meta_title.get("content").strip()

    # Return a default placeholder if no title is found
    return "Untitled Page"


def extract_links(html, root_url):
    """
    This function extracts all the URLs from the anchor tags in the HTML content of a web page. It uses BeautifulSoup to parse
    the HTML and find all 'a' tags. The function then normalizes and filters the URLs to remove query parameters, fragments, and
    URLs that do not belong to the same root URL host.

    Args:
        html (str): The HTML content of the web page from which to extract the URLs.
        root_url (str): The root URL of the web page. This is used to filter out URLs that do not belong to the same host.

    Returns:
        list: A list of extracted, normalized, and filtered URLs.
    """
    # Use BeautifulSoup to parse the HTML and extract all anchor tags
    soup = BeautifulSoup(html, "html.parser")
    anchor_tags = soup.find_all("a")

    # Extract the URLs from the anchor tags
    extracted_urls = []
    for tag in anchor_tags:
        url = tag.get("href")
        if url and url.strip():
            extracted_urls.append(url.strip())

    # Normalize the URLs (e.g., remove query parameters, fragments)
    normalized_urls = []
    for url in extracted_urls:
        parsed_url = urlparse(url)
        normalized_url = urlunparse(
            (parsed_url.scheme, parsed_url.netloc, parsed_url.path, "", "", "")
        )
        normalized_urls.append(normalized_url)

    # Remove any URL that does not belong to the same root URL host
    root_url_parts = urlparse(root_url)
    filtered_urls = [
        url
        for url in normalized_urls
        if url.startswith(root_url_parts.scheme + "://" + root_url_parts.netloc)
    ]

    # Return the list of extracted and filtered URLs
    return filtered_urls


def crawl(data_source_id, url, crawled_urls, max_pages, chatbot_id):
    """
    This function crawls a web page at a given URL and stores its content in the database. It also extracts all the links from
    the page and recursively crawls each one. The function keeps track of the URLs that have been crawled to avoid duplicates and
    stops crawling when the maximum number of pages has been reached. If an error occurs while crawling a URL, the function logs
    the error and continues with the next URL.

    Args:
        data_source_id (int): The ID of the data source from which the web page is being crawled.
        url (str): The URL of the web page to crawl.
        crawled_urls (list): A list of URLs that have already been crawled. This is used to avoid crawling the same URL multiple times.
        max_pages (int): The maximum number of pages to crawl.
        chatbot_id (int): The ID of the chatbot that initiated the crawl.

    Raises:
        requests.exceptions.RequestException: If an error occurs while sending the HTTP GET request to the URL, the function will catch this exception and do nothing.
        Exception: If any other error occurs during the crawling process, the function will catch the exception, log the error, and continue with the next URL.
    """
    # Check if the maximum page limit has been reached
    if len(crawled_urls) >= max_pages:
        return

    # Check if the URL has already been crawled
    if url in crawled_urls:
        return

    # Check if the URL ends with a binary file extension
    binary_extensions = [
        ".png",
        ".jpg",
        ".jpeg",
        ".gif",
        ".bmp",
        ".ico",
        ".tif",
        ".tiff",
        ".webp",
    ]
    if any(url.lower().endswith(ext) for ext in binary_extensions):
        """
        This block of code checks if the URL ends with a binary file extension. It has a list of binary file extensions
        (binary_extensions). The 'any' function is used with a generator expression that checks if the URL (converted to
        lower case to ensure case-insensitive matching) ends with any of the extensions in the list. If the URL does end
        with a binary file extension, the function immediately returns, effectively skipping the processing of binary files
        in the web crawling process.
        """
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
        store_crawled_page_content_to_database(
            url, response, chatbot_id, data_source_id, html
        )

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
