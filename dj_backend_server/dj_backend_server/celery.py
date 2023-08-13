# celery.py

from celery import Celery
import os

os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'dj_backend_server.settings')
app = Celery('dj_backend_server')
app.config_from_object('django.conf:settings', namespace='CELERY')
app.autodiscover_tasks()
