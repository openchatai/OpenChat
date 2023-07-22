import uuid
from django.db import models

class ChatHistory(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    chatbot_id = models.UUIDField()
    session_id = models.CharField(max_length=255, null=True, blank=True)  # New field for session ID
    from_user = models.CharField(max_length=4)  # 'user' or 'bot'
    message = models.TextField()
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'chat_histories'