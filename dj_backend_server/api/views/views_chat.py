from django.http import JsonResponse
from django.views.decorators.http import require_POST

from api.utils import get_vector_store
from api.utils.make_chain import getConversationRetrievalChain
import json
from django.views.decorators.csrf import csrf_exempt
from api.interfaces import StoreOptions
from web.models.chat_histories import ChatHistory
from django.shortcuts import get_object_or_404
from web.models.chatbot import Chatbot
from uuid import uuid4
import logging
import traceback
from web.services.chat_history_service import get_chat_history_for_retrieval_chain

logger = logging.getLogger(__name__)

@csrf_exempt
@require_POST
def chat(request):
    try:
        body = json.loads(request.body.decode('utf-8'))
        question = body.get('question')
        namespace = body.get('namespace')
        mode = body.get('mode')
        initial_prompt = body.get('initial_prompt')
        token = body.get('token')
        session_id = body.get('session_id')

        bot = get_object_or_404(Chatbot, token=token)

        if not question:
            return JsonResponse({'error': 'No question in the request'}, status=400)

        sanitized_question = question.strip().replace('\n', ' ')

        vector_store = get_vector_store(StoreOptions(namespace=namespace))
        chain = getConversationRetrievalChain(vector_store, mode, initial_prompt, memory_key=session_id)
        
        # To avoid fetching an excessively large amount of history data from the database, set a limit on the maximum number of records that can be retrieved in a single query.
        chat_history = get_chat_history_for_retrieval_chain(session_id, limit=40)
        response = chain({"question": sanitized_question, "chat_history": chat_history }, return_only_outputs=True)
        response_text = response['answer']

        ChatHistory.objects.bulk_create([
            ChatHistory(
                id=uuid4(),
                chatbot_id=bot.id,
                from_user=True,
                message=sanitized_question,
                session_id=session_id
            ),
            ChatHistory(
                id=uuid4(),
                chatbot_id=bot.id,
                from_user=False,
                message=response_text,
                session_id=session_id
            )
        ])

        return JsonResponse({'text': response_text})
    except json.JSONDecodeError:
        return JsonResponse({'error': 'Invalid JSON in request body'}, status=400)
    except Chatbot.DoesNotExist:
        return JsonResponse({'error': 'Chatbot not found'}, status=404)
    except Exception as e:
        logger.error(str(e))
        logger.error(traceback.format_exc())
        return JsonResponse({'error': 'An error occurred'}, status=500)