from django.db import models
import uuid

class CrawledPage(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    chatbot_id = models.UUIDField()
    website_data_source_id = models.UUIDField()
    url = models.CharField(max_length=255)
    title = models.CharField(max_length=255, null=True, blank=True)
    status_code = models.CharField(max_length=10, null=True, blank=True)
    aws_url = models.TextField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'crawled_pages'