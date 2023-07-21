from django.db import models
import uuid

class WebsiteDataSource(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    chatbot_id = models.UUIDField()
    root_url = models.CharField(max_length=255)
    icon = models.CharField(max_length=255, null=True, blank=True)
    vector_databased_last_ingested_at = models.DateTimeField(null=True, blank=True)
    crawling_status = models.CharField(max_length=50, default='pending')
    crawling_progress = models.FloatField(default=0.0)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'website_data_sources'
