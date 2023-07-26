from django.db import models
import uuid

class Chatbot(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    name = models.CharField(max_length=255)
    website = models.CharField(max_length=255)
    status = models.CharField(max_length=50)  # Assuming ChatbotStatusType is a string-based enum in Laravel
    prompt_message = models.TextField(blank=True, null=True)  # Assuming prompt_message can be nullable in Laravel

    def __str__(self):
        return self.name

    def settings(self):
        return self.chatbotsetting_set.all()

    def crate_or_update_setting(self, name, value):
        setting, created = self.chatbotsetting_set.get_or_create(name=name, defaults={'value': value})
        if not created:
            setting.value = value
            setting.save()

    def get_setting(self, name):
        setting = self.chatbotsetting_set.filter(name=name).first()
        return setting.value if setting else None

    def get_website_data_sources(self):
        return self.websitedatasource_set.all()

    def get_pdf_files_data_sources(self):
        return self.pdfdatasource_set.all()

    def get_codebase_data_sources(self):
        return self.codebasedatasource_set.all()

    def messages(self):
        return self.chathistory_set.all()

    class Meta:
        db_table = 'chatbot'  # Replace 'chatbot' with the actual table name in the database
