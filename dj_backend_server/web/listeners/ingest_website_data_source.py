# listeners.py

import requests
from requests.exceptions import RequestException

# @website_data_source_crawling_completed.connect
def handle_crawling_completed(chatbot_id, website_data_source_id):
    request_body = {
        'type': 'website',
        'shared_folder': str(website_data_source_id),
        'namespace': str(chatbot_id),
    }

    try:
        url = "http://localhost:8000/api/ingest/"  # Replace with the actual URL
        response = requests.post(url, json=request_body)

        if response.status_code != 200:
            raise Exception('Ingest service returned an error: ' + response.text)
    except RequestException as e:
        return