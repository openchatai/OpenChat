from django.http import JsonResponse
from django.views.decorators.http import require_POST

from api.utils import get_vector_store, make_chain
import json


@require_POST
def chat(request):
    body = json.loads(request.body.decode('utf-8'))
    question = body.get('question')
    history = body.get('history')
    namespace = body.get('namespace')
    mode = body.get('mode')
    initial_prompt = body.get('initial_prompt')

    if not question:
        return JsonResponse({'message': 'No question in the request'})

    sanitized_question = question.strip().replace('\n', ' ')

    try:
        vector_store = get_vector_store(namespace)
        chain = make_chain(vector_store, mode, initial_prompt)

        response = chain({"question": sanitized_question, "chat_history": history})

        return JsonResponse(response)
    except Exception as e:
        return JsonResponse({'error': str(e)})

