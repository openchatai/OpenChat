from django.db import models
import uuid

class CodebaseDataSource(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    chatbot_id = models.UUIDField()
    repository = models.CharField(max_length=255)
    ingested_at = models.DateTimeField(null=True, blank=True)
    ingestion_status = models.CharField(max_length=255, null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'codebase_data_sources'
