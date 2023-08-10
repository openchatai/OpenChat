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
from django.db.models import F

@csrf_exempt
@require_POST
def chat(request):
    body = json.loads(request.body.decode('utf-8'))
    question = body.get('question')
    history = body.get('history') or []
    namespace = body.get('namespace')
    mode = body.get('mode')
    initial_prompt = body.get('initial_prompt')
    token = body.get('token')
    session_id = body.get('session_id')

    bot = get_object_or_404(Chatbot, token=token)

    if not question:
        return JsonResponse({'message': 'No question in the request'})

    sanitized_question = question.strip().replace('\n', ' ')

    try:
        ChatHistory.objects.create(
            id=uuid4(),
            chatbot_id=bot.id,
            from_user=True,
            message=sanitized_question,
            session_id=session_id
        )

        vector_store = get_vector_store(StoreOptions(namespace=namespace))
        chain = getConversationRetrievalChain(vector_store, mode, initial_prompt, memory_key=session_id)        

        response = chain({"question": sanitized_question, "chat_history": [] }, return_only_outputs=True)
        r = {'text': response['answer']}


        ChatHistory.objects.create(
            id=uuid4(),
            chatbot_id=bot.id,
            from_user=False,
            message=r['text'],
            session_id=session_id
        )
        return JsonResponse(r)
    except Exception as e:
            import traceback
            print(e)
            traceback.print_exc()
            return JsonResponse({'error': str(e)})

