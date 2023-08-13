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
from web.models.crawled_pages import CrawledPages
import os
from django.http import HttpResponseNotFound, FileResponse

def image_view(request, app_id, image_name):
    image_path = os.path.join('website_data_sources/icons', image_name)
    if os.path.exists(image_path):
        return FileResponse(open(image_path, 'rb'))
    else:
        return HttpResponseNotFound()
    
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
    website_data_sources = WebsiteDataSource.objects.filter(chatbot_id=id).prefetch_related('crawled_pages')

    # Debugging only
    # for data_source in website_data_sources:
    #     for page in data_source.crawled_pages.all():
    #         print("Page:", page) 

    #         # Get index of current page
    #         page_index = data_source.crawled_pages.all().index(page)

    #         # Print raw JSON for this page
    #         print("Raw Page Data:")
    #         print(data_source._crawled_pages_cache[page_index])
    
    pdf_data_sources = PdfDataSource.objects.filter(chatbot_id=id)
    codebase_data_sources = CodebaseDataSource.objects.filter(chatbot_id=id)

    return render(request, 'settings-data.html', {'bot': bot, 'website_data_sources': website_data_sources, 'pdf_data_sources': pdf_data_sources, 'codebase_data_sources': codebase_data_sources})


def analytics_settings(request, id):
    bot = get_object_or_404(Chatbot, id=id)
    data_sources = bot.website_data_sources.all()
    return render(request, 'settings-analytics.html', {'bot': bot, 'dataSources': data_sources})


def integrations_settings(request, id):
    bot = get_object_or_404(Chatbot, id=id)
    return render(request, 'settings-integrations.html', {'bot': bot})


def data_sources_updates(request, id):
    # chatbot = get_object_or_404(Chatbot, id=id)
    website_data_sources = WebsiteDataSource.objects.filter(chatbot_id=id)
    pdf_data_sources = PdfDataSource.objects.filter(chatbot_id=id)
    return render(request, 'widgets/data-sources-updates.html', {'website_data_sources': website_data_sources, 'pdf_data_sources': pdf_data_sources})


def theme_settings(request, id):
    bot = get_object_or_404(Chatbot, id=id)
    return render(request, 'settings-theme.html', {'bot': bot})
