from django.db import models
import uuid
from .website_data_sources import WebsiteDataSource

class CrawledPages(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    chatbot_id = models.UUIDField()
    website_data_source = models.ForeignKey(WebsiteDataSource, on_delete=models.CASCADE, related_name='crawled_pages')
    url = models.URLField()
    title = models.CharField(max_length=255, blank=True, null=True)
    status_code = models.CharField(max_length=10)
    content_file= models.CharField(max_length=100)

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
