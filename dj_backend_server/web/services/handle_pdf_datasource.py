# services.py
import os
import hashlib
from django.core.exceptions import ValidationError
from django.core.files.storage import default_storage
from web.models.chatbot import Chatbot
from web.models.pdf_data_sources import PdfDataSource
from web.models.failed_jobs import FailedJob
from datetime import datetime
from uuid import uuid4
import secrets

class HandlePdfDataSource:
    def __init__(self, bot: Chatbot, files, text_data=None):
        self.bot = bot
        self.files = files
        self.text_data = text_data

    def handle(self) -> PdfDataSource:
        data_source = PdfDataSource()
        data_source.bot = self.bot

        folder_name = secrets.token_hex(20)
        folder_path = f"website_data_sources/{folder_name}"

        # If files are provided, process them
        if self.files:
            files_urls = []
            files_info_list = []
            for file in self.files:
                try:
                    # Validate file types or other conditions if necessary
                    # For example: if not file.name.endswith('.pdf'): raise ValidationError('Invalid file type')

                    # Generate a unique file name using UUID
                    file_extension = os.path.splitext(file.name)[1]
                    file_uuid_name = str(uuid4()) + file_extension
                    file_path = os.path.join(folder_path, file_uuid_name)

                    # Generate hash of the file content
                    file_hash = hashlib.md5(file.read()).hexdigest()
                    file.seek(0)  # Reset file pointer to beginning
                    
                    # Create the directory if it does not exist
                    directory = os.path.dirname(file_path)
                    os.makedirs(directory, exist_ok=True)

                    # Check if the directory was created successfully
                    if not os.path.isdir(directory):
                        raise Exception(f"Failed to create directory: {directory}")

                    # Save the file to the storage system
                    default_storage.save(file_path, file)

                    # Save file info
                    files_info = {
                        'original_name': file.name,
                        'uuid_name': file_uuid_name,
                        'hash': file_hash
                    }
                    files_urls.append(file_path)
                    files_info_list.append(files_info)

                except Exception as e:
                    # Log the exception for debugging purposes
                    # print(f"Error while uploading file: {file.name}, Error: {str(e)}")
                    # You can log the exception to a file or use a proper logging framework
                    failed_job = FailedJob(uuid=str(uuid4()), connection='default', queue='default', payload=str(files_info_list), exception=str(e),
    failed_at=datetime.now())
                    failed_job.save()
                    # You can also raise a more specific custom exception if needed
                    raise ValidationError(f"Error while uploading file: {file.name}, Error: {str(e)}")

            data_source.files = files_urls
            data_source.files_info = files_info_list
            data_source.folder_name = folder_name
            data_source.ingest_status = 'File(s) Uploaded'
        
        else:
            files_urls = []
            files_info_list = []
            # If text_data is provided, use it
            if self.text_data:
                data_source.text_data = self.text_data
                data_source.ingest_status = 'Text data processed'
            else:
                data_source.ingest_status = 'No files or text data provided'

            data_source.files = files_urls
            data_source.folder_name = "nofolderneeded"
            data_source.files_info = self.text_data

        data_source.chatbot_id = self.bot.id
        data_source.save()
        print (f"datasource: {data_source}")
        return data_source