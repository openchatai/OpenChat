from django.db import models

class FailedJob(models.Model):
    id = models.BigAutoField(primary_key=True)
    uuid = models.CharField(max_length=255, unique=True)
    connection = models.TextField()
    queue = models.TextField()
    payload = models.TextField()
    exception = models.TextField()
    failed_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'failed_jobs'