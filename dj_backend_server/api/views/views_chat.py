import json
import os
import logging
import traceback
from uuid import uuid4
from django.http import JsonResponse
from django.views.decorators.http import require_POST
from django.views.decorators.csrf import csrf_exempt
from django.shortcuts import get_object_or_404
from langchain import QAWithSourcesChain
from api.utils import get_vector_store
from api.utils.make_chain import getConversationRetrievalChain, getRetrievalQAWithSourcesChain
from api.interfaces import StoreOptions
from web.models.chat_histories import ChatHistory
from web.models.chatbot import Chatbot
from web.services.chat_history_service import get_chat_history_for_retrieval_chain

from dotenv import load_dotenv
load_dotenv()

logger = logging.getLogger(__name__)

@csrf_exempt
@require_POST
def chat(request):
    """
    This view function handles the chat interaction with a chatbot. It retrieves the chat parameters from the POST data of the request,
    validates them, and sends a request to the chatbot API. It then processes the response from the API and returns a JSON response with
    the bot's reply or an error message.

    Args:
        request (HttpRequest): The HTTP request object. The chat parameters are expected to be in the POST data of this request.

    Returns:
        JsonResponse: A JSON response containing the bot's reply if the API request was successful and the API response included a
        'text' key, an error message and a 400 status code if the question was not provided, an error message and a 500 status code
        if the API response did not include a 'text' key, or an error message and a 500 status code if an exception was raised.
    """
    try:
        body = json.loads(request.body.decode('utf-8'))
        question = body.get('question')
        namespace = body.get('namespace')
        mode = body.get('mode')
        initial_prompt = body.get('initial_prompt')
        token = body.get('token')
        session_id = body.get('session_id')
        # history = body.get('history') # @TODO not needed to pass and extract?

        bot = get_object_or_404(Chatbot, token=token)

        if not question:
            return JsonResponse({'error': 'No question in the request'}, status=400)

        sanitized_question = question.strip().replace('\n', ' ')

        vector_store = get_vector_store(StoreOptions(namespace=namespace))
        # Serialize vector_store information to JSON format for logging
        # vector_store_info = {
        #     "type": str(type(vector_store)),            
        #     "namespace": namespace
        # }
        # print (f"Vector_store_info: {json.dumps(vector_store_info)}")
        # print (f"mode: {mode} + initial_prompt: {initial_prompt} + sanitized_question: {sanitized_question} + session_id: {session_id}")
        
        response_text = get_completion_response(vector_store=vector_store, initial_prompt=initial_prompt,mode=mode, sanitized_question=sanitized_question, session_id=session_id)
        
        # print(f"Response before creating ChatHistory: {json.dumps(response_text, indent=2)}")
        if isinstance(response_text, dict) and 'text' in response_text:
            ChatHistory.objects.bulk_create([
                ChatHistory(
                    id=uuid4(),
                    chatbot_id=bot.id,
                    from_user=True,
                    message=sanitized_question,
                    session_id=session_id,
                ),
                ChatHistory(
                    id=uuid4(),
                    chatbot_id=bot.id,
                    from_user=False,
                    message=response_text['text'],
                    session_id=session_id,
                )
            ])
            return JsonResponse({'text': response_text['text']})
        
        elif isinstance(response_text, str):
            ChatHistory.objects.bulk_create([
                ChatHistory(
                    id=uuid4(),
                    chatbot_id=bot.id,
                    from_user=True,
                    message=sanitized_question,
                    session_id=session_id,
                ),
                ChatHistory(
                    id=uuid4(),
                    chatbot_id=bot.id,
                    from_user=False,
                    message=response_text,
                    session_id=session_id,
                )
            ])
            # print(f"ChatHistory created with response: {response_text}")
            return JsonResponse({'text': response_text})        
        
        else:
            # print(f"Response does not contain 'text' key: {response_text}")
            return JsonResponse({'error': 'Unexpected response from API'}, status=500)
        
    except json.JSONDecodeError:
        return JsonResponse({'error': 'Invalid JSON in request body'}, status=400)
    except Chatbot.DoesNotExist:
        return JsonResponse({'error': 'Chatbot not found'}, status=404)
    except Exception as e:
        logger.error(str(e))
        logger.error(traceback.format_exc())
        return JsonResponse({'error': 'An error occurred'}, status=500)


def get_completion_response(vector_store, mode, initial_prompt, sanitized_question, session_id):
    chain_type = os.getenv("CHAIN_TYPE", "conversation_retrieval")
    chain: QAWithSourcesChain
    if chain_type == 'retrieval_qa':
        chain = getRetrievalQAWithSourcesChain(vector_store, mode, initial_prompt)
        response = chain({"question": sanitized_question}, return_only_outputs=True)
        response_text = response['answer']
    elif chain_type == 'conversation_retrieval':
        chain = getConversationRetrievalChain(vector_store, mode, initial_prompt)
        chat_history = get_chat_history_for_retrieval_chain(session_id, limit=40)
        response = chain({"question": sanitized_question, "chat_history": chat_history}, return_only_outputs=True)
        response_text = response['answer']
    try:
        # Attempt to parse the response_text as JSON
        response_text = json.loads(response_text)
    except json.JSONDecodeError:
        # If response_text is not a JSON string, leave it as is
        pass
    #Trim markdown code block formatting if present
    if isinstance(response_text, dict) and 'text' in response_text:
        # Remove markdown code block formatting if present
        response_text['text'] = response_text['text'].replace('```', '').replace('markdown\n', '').strip()
    elif isinstance(response_text, str):
        # Remove markdown code block formatting if present
        response_text = response_text.replace('```', '').replace('markdown\n', '').strip()

    return response_text 
