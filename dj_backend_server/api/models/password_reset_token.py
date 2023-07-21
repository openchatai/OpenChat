# models.py

from django.db import models

class PasswordResetToken(models.Model):

    email = models.EmailField(primary_key=True)
    token = models.CharField(max_length=255) 
    created_at = models.DateTimeField(null=True)

    class Meta:
       db_table = 'password_reset_tokens'