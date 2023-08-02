# services.py
import os
from django.core.exceptions import ValidationError
from django.core.files.storage import default_storage
from web.models.chatbot import Chatbot
from web.models.pdf_data_sources import PdfDataSource
from uuid import uuid4
import secrets

class HandlePdfDataSource:
    def __init__(self, bot: Chatbot, files):
        self.bot = bot
        self.files = files

    def handle(self) -> PdfDataSource:
        data_source = PdfDataSource()
        data_source.bot = self.bot

        folder_name = secrets.token_hex(20)
        folder_path = f"website_data_sources/{folder_name}"

        files_urls = []
        for file in self.files:
            try:
                # Validate file types or other conditions if necessary
                # For example: if not file.name.endswith('.pdf'): raise ValidationError('Invalid file type')
                
                # Generate a unique file name using UUID
                file_extension = os.path.splitext(file.name)[1]
                file_name = str(uuid4()) + file_extension
                file_path = os.path.join(folder_path, file_name)
                
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

        data_source.chatbot_id = self.bot.id
        data_source.files = files_urls
        data_source.folder_name = folder_name

        data_source.save()
        return data_source
