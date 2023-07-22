from django.db import models

class NotionDataSource(models.Model):
    # The "id" field is automatically created by Django as the primary key
    # It's similar to Laravel's auto-incrementing primary key
    # It's not necessary to define it explicitly, but you can if you want.
    id = models.AutoField(primary_key=True)
    
    # The "timestamps" field is represented as two DateTimeField fields in Django
    # to represent the "created_at" and "updated_at" timestamps.
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        # Define the table name explicitly, as it is different from Django's default
        db_table = 'notion_data_sources'
