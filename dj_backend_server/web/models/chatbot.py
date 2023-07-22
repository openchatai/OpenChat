import uuid
from django.db import models
from django.utils import timezone

class Chatbot(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    name = models.CharField(max_length=255)
    token = models.CharField(max_length=255)
    website = models.CharField(max_length=255, null=True)
    status = models.CharField(max_length=255, default='draft')
    
    prompt_message = models.TextField()
    enhanced_privacy = models.BooleanField(default=False)
    smart_sync = models.BooleanField(default=False)
    
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    deleted_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'chatbots' # optional

    def delete(self, *args, **kwargs):
        self.deleted_at = timezone.now()
        self.save()