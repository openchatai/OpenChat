from django.urls import path
from .views import views_message, views_auth, views_ingest, views_chat
from .chatbot_info import get_chatbot_info
from .pdf_handler import upload_pdf_api
from .views.views_message import handle_feedback
from django.contrib.auth.decorators import login_required
from drf_spectacular.views import SpectacularAPIView, SpectacularRedocView, SpectacularSwaggerView


urlpatterns = [
    path('chat/search/', views_message.send_search_request, name='send_search_request'),
    path('chat/init/', views_message.init_chat, name='init_chat'),
    path('chat/send/', views_message.send_chat, name='send_chat'),
    path('rate/', handle_feedback, name='handle_feedback'),
    # website/codebase/pdf ingestion endpoint
    path('ingest/', views_ingest.ingest, name='ingest'),
    path('chat/', views_chat.chat, name='chat'),
    # Dummy auth endpoints to prevent template engine errors
    path('signin/', views_auth.signin, name='signin'),
    path('signup/', views_auth.signup, name='signup'),
    path('reset-password/', views_auth.reset_password, name='reset-password'),
    # PDF upload API endpoint
    path('upload_pdf/', upload_pdf_api, name='upload_pdf'),
    path('chatbot/<str:bot_id>/', get_chatbot_info, name='get_chatbot_info'),
    # SCHEMA
    path('schema/', login_required(SpectacularAPIView.as_view()), name='schema'),
    path('schema/swagger-ui/', login_required(SpectacularSwaggerView.as_view(url_name='schema')), name='swagger-ui'),
    path('schema/redoc/', login_required(SpectacularRedocView.as_view(url_name='schema')), name='redoc'),
    
]
