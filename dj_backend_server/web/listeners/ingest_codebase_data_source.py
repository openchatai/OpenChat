# listeners.py

import requests
from requests.exceptions import RequestException
from web.models.codebase_data_sources import CodebaseDataSource
from web.enums.ingest_status_enum import IngestStatusType
from web.signals.codebase_datasource_was_created import codebase_data_source_added
from django.core.exceptions import ObjectDoesNotExist
from django.utils.timezone import now

class IngestCodebaseDataSource:
    def handle(self, event):
        if not isinstance(event, codebase_data_source_added):
            return

        bot_id = event.get_chatbot_id()
        codebase_data_source_id = event.get_codebase_data_source_id()

        try:
            datasouce = CodebaseDataSource.objects.get(id=codebase_data_source_id)
        except ObjectDoesNotExist:
            return

        repo = datasouce.get_repository()

        request_body = {
            'type': 'codebase',
            'repo': repo,
            'namespace': bot_id,
        }

        try:
            # Call to ingest service endpoint
            url = "http://llm-server:3000/api/ingest"  # Replace with the actual URL
            response = requests.post(url, json=request_body)

            datasouce.ingested_at = now()

            if response.status_code != 200:
                datasouce.ingestion_status = IngestStatusType.FAILED
            else:
                datasouce.ingestion_status = IngestStatusType.SUCCESS

            datasouce.save()

        except RequestException as e:
            datasouce.ingested_at = now()
            datasouce.ingestion_status = IngestStatusType.FAILED
            datasouce.save()
            return
