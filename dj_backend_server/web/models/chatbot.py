from django.db import models
from django.contrib.auth.models import User
from web.enums.chatbot_initial_prompt_enum import ChatBotInitialPromptEnum

import uuid

class Chatbot(models.Model):
    id = models.CharField(max_length=36, primary_key=True)
    name = models.CharField(max_length=255, default="My first chatbot")
    website = models.CharField(max_length=255, default="https://openchat.so")
    status = models.CharField(max_length=255)  # Assuming ChatbotStatusType is a string-based enum in Laravel
    prompt_message = models.TextField(blank=True, default=ChatBotInitialPromptEnum.AI_ASSISTANT_INITIAL_PROMPT.value)
    token = models.CharField(max_length=255)  # Assuming token is a CharField
    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='chatbots')
    
    enhanced_privacy = models.BooleanField(default=False)
    smart_sync = models.BooleanField(default=False)
    created_at = models.DateTimeField(auto_now_add=True, null=True)
    updated_at = models.DateTimeField(auto_now=True, null=True)
    deleted_at = models.DateTimeField(null=True)

    def __str__(self):
        return self.name

    def settings(self):
        return self.chatbotsettings.all()  # Use the correct reverse related_name

    def create_or_update_setting(self, name, value):
        setting, created = self.chatbotsettings.get_or_create(name=name)  # Use the correct reverse related_name
        setting.value = value
        setting.save()

    def get_setting(self, name):
        try:
            setting = self.chatbotsettings.get(name=name)  # Use the correct reverse related_name
            return setting.value
        except models.DoesNotExist:
            return None

    def get_website_data_sources(self):
        return self.website_data_sources.all()  # Use the correct reverse related_name

    def get_pdf_files_data_sources(self):
        return self.pdf_data_sources.all()  # Use the correct reverse related_name

    def get_codebase_data_sources(self):
        return self.codebase_data_sources.all()  # Use the correct reverse related_name

    def get_created_at(self):
        return self.created_at

    def messages(self):
        return self.chathistory_set.all()

    class Meta:
        db_table = 'chatbots'  # Replace 'chatbot' with the actual table name in the database
