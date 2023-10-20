# listeners.py
import os
import requests
from requests.exceptions import RequestException
from web.models.codebase_data_sources import CodebaseDataSource
from web.enums.ingest_status_enum import IngestStatusType
from web.signals.codebase_datasource_was_created import codebase_data_source_added
from django.core.exceptions import ObjectDoesNotExist
from django.utils.timezone import now
from web.signals.codebase_datasource_was_created import codebase_data_source_added

@codebase_data_source_added.connect
def ingest_codebase_data_source(sender, chatbot_id, data_source_id, **kwargs):
    try:
        datasource = CodebaseDataSource.objects.get(id=data_source_id)
    except ObjectDoesNotExist:
        return

    repo = datasource.repository

    request_body = {
        'type': 'codebase',
        'repo': repo,
        'namespace': str(chatbot_id),
    }

    try:
        # Call to ingest service endpoint
        url = os.getenv('APP_URL') + "/api/ingest/" 
        response = requests.post(url, json=request_body)

        datasource.ingested_at = now()

        if response.status_code != 200:
            datasource.ingestion_status = IngestStatusType.FAILED
        else:
            datasource.ingestion_status = IngestStatusType.SUCCESS

        datasource.save()

    except RequestException as e:
        datasource.ingested_at = now()
        datasource.ingestion_status = IngestStatusType.FAILED
        datasource.save()
        return