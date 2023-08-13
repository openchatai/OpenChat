from django.db import models
from django.utils.timezone import now

class FailedJob(models.Model):
    id = models.BigAutoField(primary_key=True)
    uuid = models.CharField(max_length=255)
    connection = models.TextField()
    queue = models.TextField()
    payload = models.TextField()
    exception = models.TextField()
    failed_at = models.DateTimeField(default=now)


    class Meta:
        db_table = 'failed_jobs'