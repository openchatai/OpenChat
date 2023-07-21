# your_app/models.py
from django.db import models

class PdfDataSource(models.Model):
    # Define your existing fields here
    # For example:
    # name = models.CharField(max_length=100)
    # file = models.FileField(upload_to='pdf_files/')
    
    # Add the new field 'ingest_status' with the default value
    ingest_status_choices = (
        ('SUCCESS', 'Success'),
        ('FAILURE', 'Failure'),
        ('PENDING', 'Pending'),
    )
    ingest_status = models.CharField(
        max_length=10,
        choices=ingest_status_choices,
        default='SUCCESS',
    )

