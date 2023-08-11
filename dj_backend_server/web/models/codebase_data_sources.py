from django.db import models
from django.utils import timezone
from web.models.chatbot import Chatbot
import uuid

class CodebaseDataSource(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    repository = models.CharField(max_length=255)
    chatbot = models.ForeignKey(Chatbot, on_delete=models.CASCADE, related_name='codebase_data_sources')
    ingested_at = models.DateTimeField()
    ingestion_status = models.CharField(max_length=50)

    def set_id(self, _id):
        self.id = _id

    def get_id(self):
        return self.id

    def get_repository(self):
        return self.repository

    def set_repository(self, repository):
        self.repository = repository

    def get_chatbot_id(self):
        return self.chatbot_id

    def set_chatbot_id(self, chatbot_id):
        self.chatbot_id = chatbot_id

    def get_ingested_at(self):
        return self.ingested_at

    def set_ingested_at(self, ingested_at):
        self.ingested_at = ingested_at

    def get_ingestion_status(self):
        return self.ingestion_status

    def set_ingestion_status(self, ingestion_status):
        self.ingestion_status = ingestion_status

    def get_created_at(self):
        return self.created_at

    def get_updated_at(self):
        return self.updated_at

    class Meta:
        db_table = 'codebase_data_sources'  # Replace 'codebase_data_source' with the actual table name in the database

    def save(self, *args, **kwargs):
        if not self.ingested_at:
            self.ingested_at = timezone.now()
        super().save(*args, **kwargs)
