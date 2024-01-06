"""
This module provides an API endpoint for uploading PDF files. It handles the PDF upload process,
associates the uploaded PDFs with a chatbot based on the provided bot token, and triggers further
processing of the PDFs. The endpoint requires a POST request with the PDF files and optional flags.
"""

from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from django.views.decorators.http import require_POST
from django.shortcuts import get_object_or_404
from web.services.handle_pdf_datasource import HandlePdfDataSource
from web.models.chatbot import Chatbot
from web.signals.pdf_datasource_was_added import pdf_data_source_added
from web.enums.chatbot_initial_prompt_enum import ChatBotInitialPromptEnum
from web.enums.common_enums import ChatBotDefaults
from uuid import uuid4

@csrf_exempt
@require_POST
def upload_pdf_api(request):
    """
    API endpoint for uploading PDF files. It expects a POST request with the following parameters:
    - 'X-Bot-Token' header: A token to authenticate the chatbot.
    - 'delete_folder_flag': A flag indicating whether to delete the folder after processing (0 or 1).
    - 'pdffiles': The PDF file(s) to be uploaded. Can be a single file or multiple files.
    """
    
    bot_token = request.headers.get('X-Bot-Token')
    try:
        bot = Chatbot.objects.get(token=bot_token)
    except Chatbot.DoesNotExist:
        return JsonResponse({'error': 'Invalid token'}, status=403)

    if request.method == 'POST':
        """
        Handles the POST request to upload PDF files. It extracts the bot token, processes the uploaded files,
        creates a data source, and triggers an event to indicate that the PDF data source was added.
        """

        delete_folder_flag = request.POST.get('delete_folder_flag', '0') == '1'

        files = request.FILES.getlist('pdffiles')
        # Handle the PDF data source
        handle_pdf = HandlePdfDataSource(bot, files)
        data_source = handle_pdf.handle()
        # print (data_source)

        # Trigger the PdfDataSourceWasAdded event
        pdf_data_source_added.send(sender='create_via_pdf_flow', bot_id=bot.id, data_source_id=data_source.id, delete_folder_flag=delete_folder_flag)
        return JsonResponse({'message': 'PDF uploaded and chatbot created successfully', 'data_source_id': data_source.id, 'bot_id': bot.id})

    return JsonResponse({'error': 'Invalid request method'}, status=405)