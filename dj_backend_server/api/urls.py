# api/urls.py
from django.urls import path
from . import views

urlpatterns = [
    path('api/endpoint/', views.api_endpoint, name='api_endpoint'),
    # Add more API URL patterns as needed, following the same pattern
]