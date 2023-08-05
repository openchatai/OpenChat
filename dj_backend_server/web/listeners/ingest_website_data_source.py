# listeners.py

import requests
from requests.exceptions import RequestException
from web.models.website_data_sources import WebsiteDataSource
from web.enums.website_data_source_status_enum import WebsiteDataSourceStatusType
from web.signals.website_data_source_crawling_was_completed import website_data_source_crawling_completed
from django.core.exceptions import ObjectDoesNotExist
from django.dispatch import receiver

# @website_data_source_crawling_completed.connect
def handle_crawling_completed(chatbot_id, website_data_source_id):
    
    try:
        website_data_source = WebsiteDataSource.objects.get(id=website_data_source_id)
    except ObjectDoesNotExist:
        return

    request_body = {
        'type': 'website',
        'shared_folder': str(website_data_source_id),
        'namespace': str(chatbot_id),
    }

    try:
        # Call to ingest service endpoint
        url = "http://localhost:8000/api/ingest/"  # Replace with the actual URL
        response = requests.post(url, json=request_body)

        if response.status_code != 200:
            raise Exception('Ingest service returned an error: ' + response.text)

        website_data_source.crawling_status = WebsiteDataSourceStatusType.COMPLETED.value
        website_data_source.save()

    except RequestException as e:
        website_data_source.crawling_status = WebsiteDataSourceStatusType.FAILED.value
        website_data_source.save()
        return

