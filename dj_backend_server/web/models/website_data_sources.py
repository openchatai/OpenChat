from django.db import models
from web.models.chatbot import Chatbot
class WebsiteDataSource(models.Model):
    id = models.CharField(max_length=36, primary_key=True)
    chatbot_id = models.CharField(max_length=36, null=True)
    root_url = models.CharField(max_length=255)
    icon = models.CharField(max_length=255, null=True)
    vector_databased_last_ingested_at = models.DateTimeField(null=True)
    crawling_status = models.CharField(max_length=255, default='pending')
    crawling_progress = models.FloatField(default=0.00)
    created_at = models.DateTimeField(auto_now_add=True, null=True)
    updated_at = models.DateTimeField(auto_now=True, null=True)

    class Meta:
        db_table = 'website_data_sources'  # Replace 'website_data_source' with the actual table name in the database
