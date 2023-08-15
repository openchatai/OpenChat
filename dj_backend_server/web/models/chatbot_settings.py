from django.db import models
import uuid
from web.models.chatbot import Chatbot

class ChatbotSetting(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    chatbot_id = models.CharField(max_length=36, null=True)
    name = models.CharField(max_length=255)
    value = models.CharField(max_length=255)
    created_at = models.DateTimeField(auto_now_add=True, null=True) 
    updated_at = models.DateTimeField(auto_now=True, null=True)

    class Meta:
        db_table = 'chatbot_settings'  # Replace 'chatbot_setting' with the actual table name in the database
