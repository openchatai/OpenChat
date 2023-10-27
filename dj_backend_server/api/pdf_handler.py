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
    bot_token = request.headers.get('X-Bot-Token')
    #bot = get_object_or_404(Chatbot, token=bot_token)
    # Find the chatbot by token
    try:
        bot = get_object_or_404(Chatbot, token=bot_token)
    except Chatbot.DoesNotExist:
        return JsonResponse({'error': 'Invalid token'}, status=403)

    if request.method == 'POST':
        # name = request.POST.get('name') or ChatBotDefaults.NAME.value
        # prompt_message = request.POST.get('prompt_message') or ChatBotInitialPromptEnum.AI_ASSISTANT_INITIAL_PROMPT.value
        delete_folder_flag = 'delete_folder_flag' in request.POST

        # bot = Chatbot.objects.create(
        #     id=uuid4(),
        #     name=name,
        #     token=str(uuid4())[:20],
        #     prompt_message=prompt_message
        # )

        files = request.FILES.getlist('pdffiles')
        # Handle the PDF data source
        handle_pdf = HandlePdfDataSource(bot, files)
        data_source = handle_pdf.handle()

        # Trigger the PdfDataSourceWasAdded event
        pdf_data_source_added.send(sender='create_via_pdf_flow', bot_id=bot.id, data_source_id=data_source.id, delete_folder_flag=delete_folder_flag)
        return JsonResponse({'message': 'PDF uploaded and chatbot created successfully', 'data_source_id': data_source.id, 'bot_id': bot.id})

    return JsonResponse({'error': 'Invalid request method'}, status=405)