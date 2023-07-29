from django.db import models
import uuid
from django.utils import timezone
class WebsiteDataSource(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    chatbot_id = models.UUIDField()
    html_files = models.JSONField(default=list)
    root_url = models.URLField()
    icon = models.ImageField(upload_to='website_icons/', null=True, blank=True)
    vector_databased_last_ingested_at = models.DateTimeField(default=timezone.now)
    crawling_status = models.CharField(max_length=50)
    crawling_progress = models.FloatField(default=0.0)

    def set_id(self, _id):
        self.id = _id

    def get_id(self):
        return self.id

    def set_chatbot_id(self, chatbot_id):
        self.chatbot_id = chatbot_id

    def get_chatbot_id(self):
        return self.chatbot_id

    def set_root_url(self, root_url):
        self.root_url = root_url

    def set_icon(self, icon):
        self.icon = icon

    def set_vector_databased_last_ingested_at(self, vector_databased_last_ingested_at):
        self.vector_databased_last_ingested_at = vector_databased_last_ingested_at

    def set_crawling_status(self, crawling_status):
        self.crawling_status = crawling_status

    def get_root_url(self):
        return self.root_url

    def get_icon(self):
        if not self.icon:
            return '/dashboard/images/user-40-07.jpg'
        return self.icon.url

    def get_vector_databased_last_ingested_at(self):
        return self.vector_databased_last_ingested_at

    def get_crawling_status(self):
        return self.crawling_status

    def chatbot(self):
        return self.chatbot  # Replace with the related name of the Chatbot model (if defined)

    def set_crawling_progress(self, crawling_progress):
        self.crawling_progress = crawling_progress

    def get_crawling_progress(self):
        return self.crawling_progress

    def get_crawled_pages(self):
        return self.crawledpages_set.all()  # Replace with the related name of the CrawledPages model (if defined)

    def get_created_at(self):
        return self.created_at

    class Meta:
        db_table = 'website_data_source'  # Replace 'website_data_source' with the actual table name in the database
