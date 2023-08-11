from django.db import models
from django.contrib.contenttypes.fields import GenericForeignKey

class PersonalAccessToken(models.Model):
    id = models.AutoField(primary_key=True)
    tokenable_type = models.ForeignKey('contenttypes.ContentType', on_delete=models.CASCADE) 
    tokenable_id = models.PositiveBigIntegerField()
    tokenable = GenericForeignKey('tokenable_type', 'tokenable_id')

    name = models.CharField(max_length=255)
    token = models.CharField(unique=True, max_length=64)
    abilities = models.TextField(null=True)
    last_used_at = models.DateTimeField(null=True) 
    expires_at = models.DateTimeField(null=True)
    
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    
    class Meta:
        db_table = 'personal_access_tokens'  # Replace 'pdf_data_source' with the actual table name in the database