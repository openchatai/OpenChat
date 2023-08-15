from django.db import models
from django.utils import timezone
from web.models.chatbot import Chatbot
import uuid

class CodebaseDataSource(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    repository = models.CharField(max_length=255)
    chatbot_id = models.CharField(max_length=36, null=True)
    ingested_at = models.DateTimeField()
    ingestion_status = models.CharField(max_length=50)

    class Meta:
        db_table = 'codebase_data_sources'  # Replace 'codebase_data_source' with the actual table name in the database