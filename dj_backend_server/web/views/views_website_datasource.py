# In views.py of your Django app (my_app/views.py)
from uuid import uuid4
from django.shortcuts import render, redirect
from django.http import Http404
from web.models.chatbot import Chatbot
from web.models.website_data_sources import WebsiteDataSource
from web.utils.get_logo_from_url import get_logo_from_url
from web.signals.website_data_source_was_added import website_data_source_added

def show(request, id):
    try:
        bot = Chatbot.objects.get(id=id)
    except Chatbot.DoesNotExist:
        raise Http404("Chatbot does not exist.")

    return render(request, 'onboarding/other-data-sources-website.html', {'bot': bot})

def create(request, id):
    try:
        bot = Chatbot.objects.get(id=id)
    except Chatbot.DoesNotExist:
        raise Http404("Chatbot does not exist.")

    root_url = request.POST.get('website')
    icon = get_logo_from_url(root_url)

    data_source = WebsiteDataSource.objects.create(
        id=uuid4(),
        chatbot_id=bot,
        root_url=root_url,
        icon=icon
    )

    # adding signal
    website_data_source_added.send(sender='Other_data_source_web', bot_id=bot.id, data_source_id=data_source.id)
    return redirect('chatbot.settings-data', id=bot.id)
