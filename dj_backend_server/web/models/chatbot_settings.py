from django.db import models
import uuid

class ChatbotSetting(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    chatbot_id = models.UUIDField()
    name = models.CharField(max_length=255)
    value = models.CharField(max_length=255)

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
        db_table = 'chatbot_setting'  # Replace 'chatbot_setting' with the actual table name in the database
