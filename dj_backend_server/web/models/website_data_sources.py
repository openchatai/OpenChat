from django.db import models
import uuid
from django.utils import timezone
from web.models.chatbot import Chatbot
class WebsiteDataSource(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    chatbot = models.ForeignKey(Chatbot, on_delete=models.CASCADE, related_name='website_data_sources')
    html_files = models.JSONField(default=list)
    root_url = models.URLField()
    icon = models.ImageField(upload_to='website_icons/', null=True, blank=True)
    vector_databased_last_ingested_at = models.DateTimeField(default=timezone.now) 
    crawling_status = models.CharField(max_length=50)
    crawling_progress = models.FloatField(default=0.0)
    
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    class Meta:
        db_table = 'website_data_source'  # Replace 'website_data_source' with the actual table name in the database
