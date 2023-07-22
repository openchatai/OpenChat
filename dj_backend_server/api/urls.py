from django.urls import path
from .views import views_message

urlpatterns = [
    path('send_search_request/', views_message.send_search_request, name='send_search_request'),
    path('init_chat/', views_message.init_chat, name='init_chat'),
    path('send_chat/', views_message.send_chat, name='send_chat'),
]