from django.db import models

class Job(models.Model):
    id = models.BigAutoField(primary_key=True)
    queue = models.CharField(max_length=255, db_index=True)
    payload = models.TextField()
    attempts = models.PositiveSmallIntegerField()
    reserved_at = models.PositiveIntegerField(null=True, blank=True)
    available_at = models.PositiveIntegerField()
    created_at = models.PositiveIntegerField()

    class Meta:
        db_table = 'jobs'
