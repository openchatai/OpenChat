from django.db import models
import uuid
from web.models.chatbot import Chatbot

class ChatbotSetting(models.Model):
    id = models.CharField(max_length=36, primary_key=True) 
    chatbot = models.ForeignKey(Chatbot, on_delete=models.CASCADE, related_name='chatbot_settings')
    name = models.CharField(max_length=255)
    value = models.CharField(max_length=255)
    created_at = models.DateTimeField(auto_now_add=True, null=True) 
    updated_at = models.DateTimeField(auto_now=True, null=True)

    def set_chatbot_id(self, chatbot_id):
        self.chatbot_id = chatbot_id

    def set_name(self, name):
        self.name = name

    def set_value(self, value):
        self.value = value

    def get_id(self):
        return self.id

    def set_id(self, _id):
        self.id = _id

    def get_chatbot_id(self):
        return self.chatbot_id

    def get_name(self):
        return self.name

    def get_value(self):
        return self.value

    def chatbot(self):
        return self.chatbot  # Replace with the related name of the Chatbot model (if defined)

    class Meta:
        db_table = 'chatbot_settings'  # Replace 'chatbot_setting' with the actual table name in the database
