# listeners.py
import os
import requests
from requests.exceptions import RequestException
from web.models.pdf_data_sources import PdfDataSource
from web.enums.ingest_status_enum import IngestStatusType
from web.signals.pdf_datasource_was_added import pdf_data_source_added
from django.core.exceptions import ObjectDoesNotExist
from django.dispatch import receiver

@pdf_data_source_added.connect
def ingest_pdf_datasource(sender, **kwargs):
    bot_id = kwargs['bot_id']
    pdf_data_source_id = kwargs['data_source_id']
    delete_folder_flag = kwargs['delete_folder_flag']
    ocr_pdf_file = kwargs.get('ocr_pdf_file', False)

    try:
        pdf_data_source = PdfDataSource.objects.get(id=pdf_data_source_id)
    except ObjectDoesNotExist:
        return

    request_body = {
        'type': 'pdf',
        'shared_folder': pdf_data_source.folder_name,
        'namespace': str(bot_id),
        'delete_folder_flag': delete_folder_flag,
        'ocr_pdf_file': ocr_pdf_file,
    }

    try:
        # Call to ingest service endpoint
        url = os.getenv('APP_URL') + "/api/ingest/" 
        response = requests.post(url, json=request_body, timeout=200)

        if response.status_code != 200:
            pdf_data_source.ingest_status = IngestStatusType.FAILED.value
            pdf_data_source.save()
            return

        pdf_data_source.ingest_status = IngestStatusType.SUCCESS.value
        pdf_data_source.save()

    except RequestException as e:
        pdf_data_source.ingest_status = IngestStatusType.FAILED.value
        pdf_data_source.save()
        return

