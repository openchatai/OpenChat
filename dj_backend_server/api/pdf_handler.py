"""
This module provides an API endpoint for uploading PDF files. It handles the PDF upload process,
associates the uploaded PDFs with a chatbot based on the provided bot token, and triggers further
processing of the PDFs. The endpoint requires a POST request with the PDF files and optional flags.
"""
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from web.services.handle_pdf_datasource import HandlePdfDataSource
from web.models.chatbot import Chatbot
from web.signals.pdf_datasource_was_added import pdf_data_source_added
from drf_spectacular.utils import extend_schema, OpenApiParameter, OpenApiExample
from drf_spectacular.types import OpenApiTypes
from rest_framework.decorators import api_view, parser_classes
from rest_framework.parsers import MultiPartParser, FormParser


@extend_schema(
    methods=['POST'],
    description="Upload PDF files and associate them with a chatbot based on the provided bot token.",
    request={
        'multipart/form-data': {
            'type': 'object',
            'properties': {
                'pdffiles': {
                    'type': 'array',
                    'items': {
                        'type': 'string',
                        'format': 'binary'
                    },
                    'description': 'The PDF file(s) to be uploaded. Can be a single file or multiple files.',
                },
                'delete_folder_flag': {
                    'type': 'integer',
                    'description': 'Flag indicating whether to delete the folder after processing (0 or 1)',
                },
                'ocr_pdf_file': {
                    'type': 'integer',
                    'description': 'Flag indicating that the file needs to be sent to OCR API (0 or 1)',
                }
            }
        }
    },
    responses={200: OpenApiTypes.OBJECT},
    parameters=[
        OpenApiParameter(name='X-Bot-Token', description="Token to authenticate the chatbot", required=True, type=OpenApiTypes.STR, location=OpenApiParameter.HEADER),
    ],
    examples=[
        OpenApiExample(
            name='Example upload',
            description='An example of a PDF file upload',
            request_only=True,
            value={
                'pdffiles': 'file content here',
                'delete_folder_flag': 1,
                'ocr_pdf_file': 1,
            }
        ),
    ],
)
@api_view(['POST'])
@parser_classes((MultiPartParser, FormParser))
def upload_pdf_api(request):
    """
    API endpoint for uploading PDF files. It expects a POST request with the following parameters:
    - 'X-Bot-Token' header: A token to authenticate the chatbot.
    - 'delete_folder_flag': A flag indicating whether to delete the folder after processing (0 or 1).
    - 'ocr_pdf_file': A flag indicating that the file need to be send to OCR API. (0 or 1).
    - 'pdffiles': The PDF file(s) to be uploaded. Can be a single file or multiple files.
    """
    
    bot_token = request.headers.get('X-Bot-Token')
    try:
        bot = Chatbot.objects.get(token=bot_token)
    except Chatbot.DoesNotExist:
        return JsonResponse({'error': 'Invalid token'}, status=403)

    delete_folder_flag = request.POST.get('delete_folder_flag', '0') == '1'
    ocr_pdf_file = request.POST.get('ocr_pdf_file', '0') == '1'
    files = request.FILES.getlist('pdffiles')
    text_data = request.POST.get('text_data', '')

    # Handle the PDF data source
    handle_pdf = HandlePdfDataSource(bot, files)
    data_source = handle_pdf.handle()
    print (f"text_data: {data_source}")

    # Trigger the PdfDataSourceWasAdded event
    pdf_data_source_added.send(sender='create_via_pdf_flow', bot_id=bot.id, data_source_id=data_source.id, delete_folder_flag=delete_folder_flag, ocr_pdf_file=ocr_pdf_file, text_data=text_data)
    return JsonResponse({'message': 'PDF uploaded and chatbot created successfully', 'data_source_id': data_source.id, 'bot_id': bot.id})
    

