# listeners.py
import os
import requests
from requests.exceptions import RequestException

def handle_crawling_completed(chatbot_id, website_data_source_id):
    """
    This function is triggered when the crawling (crawler.py) of a website data source is completed. It sends a POST request to an ingest
    service to start the ingestion of the crawled data. The request body includes the type of the data source ('website'), the
    ID of the website data source (used as the 'shared_folder' parameter), and the ID of the chatbot (used as the 'namespace'
    parameter). The URL of the ingest service is retrieved from an environment variable ('APP_URL'). If the ingest service
    returns a status code other than 200, the function raises an exception.

    Args:
        chatbot_id (int): The ID of the chatbot that initiated the crawl.
        website_data_source_id (int): The ID of the website data source that has been crawled.

    Raises:
        Exception: If the ingest service returns a status code other than 200, the function raises an exception with a message
        that includes the response text from the ingest service.
    """
    request_body = {
        'type': 'website',
        'shared_folder': str(website_data_source_id),
        'namespace': str(chatbot_id),
    }

    try:
        url = os.getenv('APP_URL') + "/api/ingest/" 
        response = requests.post(url, json=request_body)

        if response.status_code != 200:
            raise Exception('Ingest service returned an error: ' + response.text)
    except RequestException as e:
        return