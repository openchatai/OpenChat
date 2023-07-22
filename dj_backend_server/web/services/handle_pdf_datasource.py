# services.py
import os
import random
import string
from django.core.exceptions import ValidationError
from django.core.files.storage import default_storage
from web.models import chatbot, pdf_data_sources
from uuid import uuid4

class HandlePdfDataSource:
    def __init__(self, bot: chatbot, files):
        self.bot = bot
        self.files = files

    def handle(self) -> pdf_data_sources:
        data_source = pdf_data_sources()
        data_source.bot = self.bot

        folder_name = ''.join(random.choices(string.ascii_letters, k=20))

        files_urls = []
        for file in self.files:
            try:
                # Validate file types or other conditions if necessary
                # For example: if not file.name.endswith('.pdf'): raise ValidationError('Invalid file type')
                
                # Generate a unique file name using UUID
                file_name = str(uuid4()) + '.' + os.path.splitext(file.name)[1]
                file_path = os.path.join(folder_name, file_name)
                
                # Save the file to the storage system
                default_storage.save(file_path, file)
                files_urls.append(file_path)
            except Exception as e:
                # Log the exception for debugging purposes
                print(f"Error while uploading file: {file.name}, Error: {str(e)}")
                # You can log the exception to a file or use a proper logging framework
                # For example: logger.error(f"Error while uploading file: {file.name}, Error: {str(e)}")
                # You can also raise a more specific custom exception if needed
                raise ValidationError(f"Error while uploading file: {file.name}, Error: {str(e)}")

        data_source.files = files_urls
        data_source.folder_name = folder_name

        data_source.save()
        return data_source
