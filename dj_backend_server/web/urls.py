# api/urls.py
from django.urls import path
from .views import views_chatbot_settings, views_onboarding, views_chatbot, views_pdf_data_source, views_website_datasource;

urlpatterns = [
    # Dashboard
    # path('', views.index, name='index'),

    # Chatbot Settings
    path('app/<int:id>/', views_chatbot_settings.general_settings, name='chatbot.settings'),
    path('app/<int:id>/delete/', views_chatbot_settings.delete_bot, name='chatbot.settings.delete'),
    path('app/<int:id>/', views_chatbot_settings.general_settings_update, name='chatbot.settings.update'),
    path('app/<int:id>/try-and-share/', views_chatbot_settings.theme_settings, name='chatbot.settings-theme'),
    path('app/<int:id>/data/', views_chatbot_settings.data_settings, name='chatbot.settings-data'),
    path('app/<int:id>/analytics/', views_chatbot_settings.analytics_settings, name='chatbot.settings-analytics'),
    path('app/<int:id>/integrations/', views_chatbot_settings.integrations_settings, name='chatbot.settings-integrations'),
    path('app/<int:id>/history/', views_chatbot_settings.history_settings, name='chatbot.settings-history'),
    path('widget/data-sources-updates/<int:id>/', views_chatbot_settings.data_sources_updates, name='widget.data-sources-updates'),
    path('widget/chat-history/<int:id>/<int:session_id>/', views_chatbot_settings.get_history_by_session_id, name='widget.chat-history'),

    # Onboarding Frontend
    path('onboarding/welcome/', views_onboarding.welcome, name='onboarding.welcome'),
    path('onboarding/data-source/', views_onboarding.data_sources, name='onboarding.data-source'),
    path('onboarding/website/', views_onboarding.data_sources_website, name='onboarding.website'),
    path('onboarding/pdf/', views_onboarding.data_sources_pdf, name='onboarding.pdf'),
    path('onboarding/codebase/', views_onboarding.data_sources_codebase, name='onboarding.codebase'),
    path('onboarding/<int:id>/config/', views_onboarding.config, name='onboarding.config'),
    path('onboarding/<int:id>/done/', views_onboarding.done, name='onboarding.done'),

    # Onboarding Backend
    path('onboarding/website/', views_chatbot.create_via_website_flow, name='onboarding.website.create'),
    path('onboarding/pdf/', views_chatbot.create_via_pdf_flow, name='onboarding.pdf.create'),
    path('onboarding/codebase/', views_chatbot.create_via_codebase_flow, name='onboarding.codebase.create'),
    path('onboarding/<int:id>/config/', views_chatbot.update_character_settings, name='onboarding.config.create'),

    path('app/<int:id>/data/pdf/', views_pdf_data_source.show_pdf_data_sources, name='onboarding.other-data-sources-pdf'),
    path('app/<int:id>/data/pdf/', views_pdf_data_source.create_pdf_data_source, name='onboarding.other-data-sources-pdf.create'),

    path('app/<int:id>/data/web/', views_website_datasource.show, name='onboarding.other-data-sources-web'),
    path('app/<int:id>/data/web/', views_website_datasource.create, name='onboarding.other-data-sources-web.create'),

    # Chat URL
    path('chat/<str:token>/', views_chatbot.get_chat_view, name='chat'),
    path('chat/<str:token>/send-message/', views_chatbot.send_message, name='sendMessage'),
]