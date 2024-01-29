# api/urls.py
from django.urls import path, include
from django.conf.urls.i18n import i18n_patterns
from web.views import views_chatbot_settings, views_onboarding, views_chatbot, views_pdf_data_source, views_website_datasource, views_chatbot_createuser, views_errors;

urlpatterns = [
    path('i18n/', include('django.conf.urls.i18n')),
    # Dashboard
    path('', views_chatbot.index, name='index'),
    path('login', views_chatbot.login_view, name='login'),
    path('logout', views_chatbot.logout_view, name='logout'),
    path('modify_user/', views_chatbot_createuser.modify_user, name='modify_user'),
    path('errors/', views_errors.errors_check, name='checkerrors'),


    # Chatbot Settings
    path('app/<uuid:id>/', views_chatbot_settings.general_settings, name='chatbot.settings'),
    path('app/<uuid:id>/delete/', views_chatbot_settings.delete_bot, name='chatbot.settings.delete'),
    path('app/<uuid:id>/update', views_chatbot_settings.general_settings_update, name='chatbot.settings.update'),
    path('app/<uuid:id>/try-and-share/', views_chatbot_settings.theme_settings, name='chatbot.settings-theme'),
    path('app/<uuid:id>/data/', views_chatbot_settings.data_settings, name='chatbot.settings-data'),
    path('app/<int:id>/analytics/', views_chatbot_settings.analytics_settings, name='chatbot.settings-analytics'),
    path('app/<int:id>/integrations/', views_chatbot_settings.integrations_settings, name='chatbot.settings-integrations'),
    path('app/<uuid:id>/history/', views_chatbot_settings.history_settings, name='chatbot.settings-history'),
    path('widget/data-sources-updates/<uuid:id>/', views_chatbot_settings.data_sources_updates, name='widget.data-sources-updates'),
    path('widget/chat-history/<uuid:id>/<str:session_id>/', views_chatbot_settings.get_history_by_session_id, name='widget.chat-history'),
    path('app/<int:id>/delete_file/', views_chatbot.delete_file, name='delete_file'),
    path('createuser/', views_chatbot_createuser.createuser, name='createuser'),
    path('createuser/success/', views_chatbot_createuser.createuser_success, name='createuser_success'),


    # Onboarding Frontend
    path('onboarding/welcome/', views_onboarding.welcome, name='onboarding.welcome'),
    path('onboarding/data-source/', views_onboarding.data_sources, name='onboarding.data-source'),
    path('onboarding/website/', views_onboarding.data_sources_website, name='onboarding.website'),
    path('onboarding/pdf/', views_onboarding.data_sources_pdf, name='onboarding.pdf'),
    path('onboarding/codebase/', views_onboarding.data_sources_codebase, name='onboarding.codebase'),
    path('onboarding/<uuid:id>/config/', views_onboarding.config, name='onboarding.config'),
    path('onboarding/<uuid:id>/done/', views_onboarding.done, name='onboarding.done'),

    # Onboarding Backend
    path('onboarding/website/create/', views_chatbot.create_via_website_flow, name='onboarding.website.create'),
    path('onboarding/pdf/create', views_chatbot.create_via_pdf_flow, name='onboarding.pdf.create'),
    path('onboarding/skip-pdf-upload/', views_onboarding.skip_pdf_upload, name='onboarding.skip_pdf_upload'),
    path('onboarding/codebase/create', views_chatbot.create_via_codebase_flow, name='onboarding.codebase.create'),
    path('onboarding/<uuid:id>/config/create', views_chatbot.update_character_settings, name='onboarding.config.create'),

    path('app/<uuid:id>/data/pdf/', views_pdf_data_source.show, name='onboarding.other-data-sources-pdf'),
    path('app/<uuid:id>/data/pdf/create', views_pdf_data_source.create, name='onboarding.other-data-sources-pdf.create'),

    path('app/<uuid:id>/data/web/', views_website_datasource.show, name='onboarding.other-data-sources-web'),
    path('app/<uuid:id>/data/web/create', views_website_datasource.create, name='onboarding.other-data-sources-web.create'),

    path('app/<uuid:app_id>/data/<path:image_name>', views_chatbot_settings.image_view, name="image-data"),
    # Chat URL
    path('chat/<str:token>/', views_chatbot.get_chat_view, name='chat'),
    path('chat/<str:token>/send-message/', views_chatbot.send_message, name='sendMessage'),
    path('website_data_sources/<path:file_path>', views_chatbot_settings.serve_website_data_source_file, name='website_data_source_file'),
]