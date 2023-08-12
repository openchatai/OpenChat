from django.db import models
from web.models.chatbot import Chatbot

class ChatHistory(models.Model):
    id = models.CharField(max_length=36, primary_key=True)
    chatbot_id = models.CharField(max_length=36, null=True)
    session_id = models.CharField(max_length=255, null=True)
    from_user = models.CharField(max_length=255, db_column="from")
    message = models.TextField()
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)



    def set_id(self, _id):
        self.id = _id

    def is_from_user(self):
        return self.from_user

    def is_from_bot(self):
        return not self.from_user

    def set_from_user(self):
        self.from_user = True

    def set_from_bot(self):
        self.from_user = False

    def set_message(self, message):
        self.message = message

    def get_message(self):
        return self.message

    def get_created_at(self):
        return self.created_at

    def set_chatbot_id(self, chatbot_id):
        self.chatbot_id = chatbot_id

    def set_session_id(self, session_id):
        self.session_id = session_id

    class Meta:
        db_table = 'chat_histories'  # Replace 'chat_history' with the actual table name in the database