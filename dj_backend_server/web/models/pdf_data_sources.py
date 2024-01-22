from django.db import models
from web.models.chatbot import Chatbot
import uuid, os, html, shutil

class PdfDataSource(models.Model):
    id = models.AutoField(primary_key=True)
    chatbot = models.ForeignKey(Chatbot, related_name='pdf_data_sources', db_column='chatbot_id', on_delete=models.SET_NULL, null=True)
    files = models.JSONField()
    files_info = models.JSONField(null=True)
    folder_name = models.CharField(max_length=255, null=True)
    created_at = models.DateTimeField(auto_now_add=True, null=True)
    updated_at = models.DateTimeField(auto_now=True, null=True)
    ingest_status = models.CharField(max_length=255, default='success')

    def set_id(self, _id):
        self.id = _id

    def get_id(self):
        return self.id

    def set_chatbot(self, chatbot):
        self.chatbot = chatbot

    def get_chatbot(self):
        return self.chatbot

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

    def set_files_info(self, files_info):
        self.files_info = files_info

    def get_files_info(self):
        return self.files_info

    def delete_files(self):
        folder_path = f"/app/website_data_sources/{self.folder_name}"
        print (f"FOLDER: {folder_path}")
        if os.path.exists(folder_path):
            shutil.rmtree(folder_path)
            return f"All files in folder {self.folder_name} have been deleted."
        else:
            return "No files were deleted or folder does not exist."

    class Meta:
        db_table = 'pdf_data_sources'  # Replace 'pdf_data_source' with the actual table name in the database