from django.db import models
import uuid

class PdfDataSource(models.Model):
    # Using UUIDField to represent the "id" field as a UUID primary key
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    
    # Using UUIDField to represent the "chatbot_id" field as a UUID
    chatbot_id = models.UUIDField()
    
    # Using JSONField to represent the "files" field as a JSON data type
    files = models.JSONField()
    
    # Using CharField to represent the "folder_name" field as a string
    folder_name = models.CharField(max_length=255, blank=True, null=True)
    
    # The "timestamps" field is represented as two DateTimeField fields in Django
    # to represent the "created_at" and "updated_at" timestamps.
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        # Define the table name explicitly, as it is different from Django's default
        db_table = 'pdf_data_sources'
