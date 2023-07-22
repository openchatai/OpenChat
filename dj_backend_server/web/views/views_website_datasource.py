# In views.py of your Django app (my_app/views.py)
import uuid
from django.shortcuts import render, redirect
from django.http import Http404
from web.models.chatbot import Chatbot
from web.models.website_data_sources import WebsiteDataSource
from web.utils import get_logo_from_url

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
        id=uuid.uuid4(),
        chatbot=bot,
        root_url=root_url,
        icon=icon
    )

    # Assuming you have created a Django signal for the event WebsiteDataSourceWasAdded
    # Replace this with your actual signal handling logic
    # Refer to Django signals documentation for more details on signals.
    # For example: website_data_source_was_added.send(sender=WebsiteDataSource, bot_id=bot.id, data_source_id=data_source.id)

    return redirect('chatbot.settings-data', id=bot.id)
