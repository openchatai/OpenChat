# chatbot/views.py

from django.shortcuts import render, redirect, get_object_or_404
from django.core.exceptions import ValidationError
from django.http import HttpResponse
from web.models.chatbot import Chatbot
from web.models.chat_histories import ChatHistory
from web.models.website_data_sources import WebsiteDataSource
from web.models.pdf_data_sources import PdfDataSource
from web.models.codebase_data_sources import CodebaseDataSource
from web.enums.chatbot_initial_prompt_enum import ChatBotInitialPromptEnum
from django.db.models import Count, Min


def general_settings(request, id):
    bot = get_object_or_404(Chatbot, id=id)
    return render(request, 'settings.html', {'bot': bot})


def delete_bot(request, id):
    bot = get_object_or_404(Chatbot, id=id)
    bot.delete()
    return redirect('index')


def general_settings_update(request, id):
    bot = get_object_or_404(Chatbot, id=id)

    if request.method == 'POST':
        name = request.POST.get('name')
        if not name:
            raise ValidationError("Name field is required.")
        
        bot.name = name
        bot.prompt_message = request.POST.get('prompt_message', ChatBotInitialPromptEnum.AI_ASSISTANT_INITIAL_PROMPT.value)
        bot.save()
        return redirect('chatbot.settings', id=id)

    return HttpResponse("Method not allowed.", status=405)


def history_settings(request, id):
    bot = get_object_or_404(Chatbot, id=id)
    chat_history = ChatHistory.objects.values('session_id').annotate(total_messages=Count('*'), first_message=Min('created_at')).filter(chatbot_id=bot.id).order_by('-first_message')
    return render(request, 'settings-history.html', {'bot': bot, 'chatHistory': chat_history})


def get_history_by_session_id(request, id, session_id):
    bot = get_object_or_404(Chatbot, id=id)
    chat_history = ChatHistory.objects.filter(chatbot_id=bot.id, session_id=session_id).order_by('created_at')
    return render(request, 'widgets/chat-history.html', {'chatHistory': chat_history})


def data_settings(request, id):
    bot = get_object_or_404(Chatbot, id=id)
    website_data_sources = WebsiteDataSource.objects.filter(chatbot_id=id)
    pdf_data_sources = PdfDataSource.objects.filter(chatbot_id=id)
    codebase_data_sources = CodebaseDataSource.objects.filter(chatbot_id=id)

    print("data sources : ", codebase_data_sources)
    return render(request, 'settings-data.html', {'bot': bot, 'websiteDataSources': website_data_sources, 'pdfDataSources': pdf_data_sources, 'codebaseDataSources': codebase_data_sources})


def analytics_settings(request, id):
    bot = get_object_or_404(Chatbot, id=id)
    data_sources = bot.website_data_sources.all()
    return render(request, 'settings-analytics.html', {'bot': bot, 'dataSources': data_sources})


def integrations_settings(request, id):
    bot = get_object_or_404(Chatbot, id=id)
    return render(request, 'settings-integrations.html', {'bot': bot})


def data_sources_updates(request, id):
    # bot = get_object_or_404(Chatbot, id=id)
    data_sources = WebsiteDataSource.objects.filter(chatbot_id=id)
    pdf_data_sources = PdfDataSource.objects.filter(chatbot_id=id)
    return render(request, 'widgets/data-sources-updates.html', {'dataSources': data_sources, 'pdfDataSources': pdf_data_sources})


def theme_settings(request, id):
    bot = get_object_or_404(Chatbot, id=id)
    return render(request, 'settings-theme.html', {'bot': bot})
