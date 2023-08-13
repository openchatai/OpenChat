from django.db import models
from web.models.chatbot import Chatbot
import uuid

class PdfDataSource(models.Model):
    id = models.CharField(max_length=36, primary_key=True)
    chatbot_id = models.CharField(max_length=36, null=True)
    files = models.JSONField()
    folder_name = models.CharField(max_length=255, null=True)
    created_at = models.DateTimeField(auto_now_add=True, null=True)
    updated_at = models.DateTimeField(auto_now=True, null=True)
    ingest_status = models.CharField(max_length=255, default='success')

    def set_id(self, _id):
        self.id = _id

    def get_id(self):
        return self.id

    def set_chatbot_id(self, chatbot_id):
        self.chatbot_id = chatbot_id

    def get_chatbot_id(self):
        return self.chatbot_id

    def set_files(self, files):
        self.files = files

    def set_folder_name(self, folder_name):
        self.folder_name = folder_name

    def get_folder_name(self):
        return self.folder_name

    def get_files(self):
        return self.files

    def get_created_at(self):
        return self.created_at

    def set_status(self, status):
        self.ingest_status = status

    def get_status(self):
        return self.ingest_status

    class Meta:
        db_table = 'pdf_data_sources'  # Replace 'pdf_data_source' with the actual table name in the database
