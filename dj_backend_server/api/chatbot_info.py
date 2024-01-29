from django.http import JsonResponse
from web.models.chatbot import Chatbot
from drf_spectacular.utils import extend_schema, OpenApiParameter
from drf_spectacular.types import OpenApiTypes
from rest_framework.decorators import api_view, parser_classes
from rest_framework.parsers import MultiPartParser, FormParser


@extend_schema(
    methods=['GET'],
    description="Get Chatbot info based on Bot ID",
    request=None,
)
@api_view(['GET'])
def get_chatbot_info(request, bot_id):
    '''
    Easy way to get chatbot info based on Bot ID
    '''
    try:
        bot = Chatbot.objects.get(id=bot_id)
        return JsonResponse({'id': bot.id, 'name': bot.name}, status=200)
    except Chatbot.DoesNotExist:
        return JsonResponse({'error': 'Chatbot not found'}, status=404)
