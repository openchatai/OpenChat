# listeners.py

import requests
from requests.exceptions import RequestException
from models.pdf_data_sources import PdfDataSource
from enums.ingest_status_enum import IngestStatusType
from signals.pdf_datasource_was_added import pdf_data_source_added
from django.core.exceptions import ObjectDoesNotExist
from django.dispatch import receiver

@receiver(pdf_data_source_added)
def ingest_pdf_datasource(self, **kwargs):
    # if not isinstance(event, pdf_data_source_added):
    #     return

    bot_id = kwargs['bot_id']
    pdf_data_source_id = kwargs['data_source_id']

    try:
        pdf_data_source = PdfDataSource.objects.get(id=pdf_data_source_id)
    except ObjectDoesNotExist:
        return

    request_body = {
        'type': 'pdf',
        'shared_folder': pdf_data_source.folder_name,
        'namespace': bot_id,
    }

    try:
        # Call to ingest service endpoint
        url = "http://llm-server:3000/api/ingest"  # Replace with the actual URL
        response = requests.post(url, json=request_body, timeout=200)

        if response.status_code != 200:
            pdf_data_source.status = IngestStatusType.FAILED
            pdf_data_source.save()
            return

        pdf_data_source.status = IngestStatusType.SUCCESS
        pdf_data_source.save()

    except RequestException as e:
        pdf_data_source.status = IngestStatusType.FAILED
        pdf_data_source.save()
        return

