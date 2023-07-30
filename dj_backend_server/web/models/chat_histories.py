from django.db import models
import uuid

class ChatHistory(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    chatbot_id = models.UUIDField()
    from_user = models.BooleanField(default=False)
    message = models.TextField()
    session_id = models.CharField(max_length=255)
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

    def chatbot(self):
        return self.chatbot  # Replace with the related name of the Chatbot model (if defined)

    class Meta:
        db_table = 'chat_history'  # Replace 'chat_history' with the actual table name in the database
