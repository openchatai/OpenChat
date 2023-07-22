# channels/routing.py
from django.urls import path
from .consumers import CustomConsumer

websocket_urlpatterns = [
    path('ws/some_path/', CustomConsumer.as_asgi()),
    # Add more WebSocket URL patterns and corresponding consumers as needed
]
