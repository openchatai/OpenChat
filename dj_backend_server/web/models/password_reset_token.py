# models.py

from django.db import models

class PasswordResetToken(models.Model):
    email = models.CharField(max_length=255)
    token = models.CharField(max_length=255)
    created_at = models.DateTimeField(auto_now_add=True, null=True)


    class Meta:
       db_table = 'password_reset_tokens'