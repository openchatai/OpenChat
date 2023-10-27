from django.db import models
import uuid
from .website_data_sources import WebsiteDataSource
from web.models.chatbot import Chatbot

class CrawledPages(models.Model):
    id = models.AutoField(primary_key=True)
    chatbot_id = models.CharField(max_length=36, null=True)
    website_data_source = models.ForeignKey(WebsiteDataSource, on_delete=models.CASCADE, related_name='crawled_pages')
    url = models.CharField(max_length=255)
    title = models.CharField(max_length=255, null=True)
    status_code = models.CharField(max_length=255, null=True)
    aws_url = models.TextField(null=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    content_file= models.CharField(max_length=255, null=True)

    def get_id(self):
        return self.id

    def get_chatbot_id(self):
        return self.chatbot_id

    def get_website_data_source_id(self):
        return self.website_data_source_id

    def get_url(self):
        return self.url

    def get_title(self):
        return self.title

    def get_status_code(self):
        return self.status_code

    def set_id(self, _id):
        self.id = _id

    def set_chatbot_id(self, chatbot_id):
        self.chatbot_id = chatbot_id

    def set_website_data_source_id(self, website_data_source_id):
        self.website_data_source_id = website_data_source_id

    def set_url(self, url):
        self.url = url

    def set_title(self, title):
        self.title = title

    def set_status_code(self, status_code):
        self.status_code = status_code

    def get_created_at(self):
        return self.created_at

    class Meta:
        db_table = 'crawled_pages'  # Replace 'crawled_pages' with the actual table name in the database
