import json
from uuid import uuid4

from django.http import JsonResponse, HttpResponseRedirect
from django.shortcuts import render
from django.urls import reverse

from api.models.chatbot import Chatbot
from api.models.chat_histories import ChatHistory
from api.models.codebase_data_sources import CodebaseDataSource


def index(request):
    chatbots = Chatbot.objects.all()
    return render(request, 'index.html', {'chatbots': chatbots})


def create_via_website_flow(request):
    if request.method == 'POST':
        name = request.POST.get('name')
        website = request.POST.get('website')
        prompt_message = request.POST.get('prompt_message')

        chatbot = chatbot.objects.create(
            id=uuid4(),
            name=name,
            token=str(uuid4())[:20],
            website=website,
            prompt_message=prompt_message
        )

        # Trigger the ChatbotWasCreated event (if using Django signals or channels)

        return HttpResponseRedirect(reverse('onboarding.config', args=[str(chatbot.id)]))


def create_via_pdf_flow(request):
    if request.method == 'POST':
        name = request.POST.get('name')
        prompt_message = request.POST.get('prompt_message')

        chatbot = chatbot.objects.create(
            id=uuid4(),
            name=name,
            token=str(uuid4())[:20],
            prompt_message=prompt_message
        )

        files = request.FILES.getlist('pdffiles')
        # Handle the PDF data source (you need to create the HandlePdfDataSource class)
        # dataSource = HandlePdfDataSource(chatbot, files).handle()

        # Trigger the PdfDataSourceWasAdded event (if using Django signals or channels)

        return HttpResponseRedirect(reverse('onboarding.config', args=[str(chatbot.id)]))


def update_character_settings(request):
    if request.method == 'POST':
        chatbot_id = request.POST.get('chatbot_id')
        character_name = request.POST.get('character_name')

        chatbot = chatbot.objects.get(id=chatbot_id)
        chatbot.create_or_update_setting('character_name', character_name)

        return HttpResponseRedirect(reverse('onboarding.done', args=[str(chatbot.id)]))


def send_message(request, token):
    if request.method == 'POST':
        bot = Chatbot.objects.get(token=token)
        # Retrieve and process the request data

        # Call the API (if needed) to send the message to the chatbot
        response_data = {
            'botReply': 'Bot response here',
            'sources': ['Source Document 1', 'Source Document 2'],
        }

        return JsonResponse(response_data)


def get_chat_view(request, token):
    bot = Chatbot.objects.get(token=token)
    # Initiate a cookie (if needed) if it doesn't exist

    return render(request, 'chat.html', {'bot': bot})


def create_via_codebase_flow(request):
    if request.method == 'POST':
        name = request.POST.get('name')
        prompt_message = request.POST.get('prompt_message')
        repo_url = request.POST.get('repo_url')

        chatbot = chatbot.objects.create(
            id=uuid4(),
            name=name,
            token=str(uuid4())[:20],
            prompt_message=prompt_message
        )

        CodebaseDataSource.objects.create(
            id=uuid4(),
            chatbot_id=chatbot.id,
            repository=repo_url
        )

        # Trigger the CodebaseDataSourceWasAdded event (if using Django signals or channels)

        return HttpResponseRedirect(reverse('onboarding.config', args=[str(chatbot.id)]))
