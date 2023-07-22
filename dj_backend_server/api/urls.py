from django.urls import path
from .views import views_message, views_auth

urlpatterns = [
    path('send_search_request/', views_message.send_search_request, name='send_search_request'),
    path('init_chat/', views_message.init_chat, name='init_chat'),
    path('send_chat/', views_message.send_chat, name='send_chat'),

    # Dummy auth endpoints to prevent template engine errors
    path('signin/', views_auth.signin, name='signin'),
    path('signup/', views_auth.signup, name='signup'),
    path('reset-password/', views_auth.reset_password, name='reset-password'),
]