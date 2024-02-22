import json
import os
import traceback
from uuid import uuid4
from django.conf import settings
from django.conf import settings
from django.http import JsonResponse
from django.views.decorators.http import require_POST
from django.views.decorators.csrf import csrf_exempt
from django.shortcuts import get_object_or_404
import logging.config
from langchain.chains import QAWithSourcesChain
from api.utils import get_vector_store
from api.utils.make_chain import (
    getConversationRetrievalChain,
    getRetrievalQAWithSourcesChain,
)
from api.interfaces import StoreOptions
from web.models.chat_histories import ChatHistory
from web.models.chatbot import Chatbot
from web.services.chat_history_service import get_chat_history_for_retrieval_chain
from dotenv import load_dotenv

logging.config.dictConfig(settings.LOGGING)
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

        body = json.loads(request.body.decode("utf-8"))
        question = body.get("question")
        namespace = body.get("namespace")
        mode = body.get("mode")
        initial_prompt = body.get("initial_prompt")
        token = body.get("token")
        session_id = body.get("session_id")
        metadata = body.get("metadata", {})

        logger.debug(f"Request body parsed: {body}")
        bot = get_object_or_404(Chatbot, token=token)
        if not question:
            return JsonResponse({"error": "No question in the request"}, status=400)
        sanitized_question = question.strip().replace("\n", " ")
        vector_store = get_vector_store(StoreOptions(namespace=namespace))

        response_text, metadata = get_completion_response(
            vector_store=vector_store,
            initial_prompt=initial_prompt,
            mode=mode,
            sanitized_question=sanitized_question,
            session_id=session_id,
            metadata=metadata,
        )

        if isinstance(response_text, dict) and "text" in response_text:
            ChatHistory.objects.bulk_create(
                [
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
                        message=response_text["text"],
                        session_id=session_id,
                    ),
                ]
            )
            logger.debug(
                f"Response after creating ChatHistory: {json.dumps(response_text, indent=2)}, metadata: {metadata}"
            )
            return JsonResponse({"text": response_text, "metadata": metadata})

        elif isinstance(response_text, str):
            ChatHistory.objects.bulk_create(
                [
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
                    ),
                ]
            )
            logger.debug(
                f"Response after creating ChatHistory 2: {json.dumps(response_text, indent=2)}, metadata: {metadata}"
            )
            return JsonResponse({"text": response_text, "metadata": metadata})

        else:
            return JsonResponse({"error": "Unexpected response from API"}, status=500)

    except json.JSONDecodeError:
        return JsonResponse({"error": "Invalid JSON in request body"}, status=400)
    except Chatbot.DoesNotExist:
        return JsonResponse({"error": "Chatbot not found"}, status=404)
    except Exception as e:
        logger.error(str(e))
        logger.error(traceback.format_exc())
        return JsonResponse({"error": "An error occurred"}, status=500)


def get_completion_response(
    vector_store, mode, initial_prompt, sanitized_question, session_id, metadata
):
    """
    This function generates a response based on a given question. It uses either the 'retrieval_qa' or 'conversation_retrieval'
    chain type to generate the response, depending on the environment variable "CHAIN_TYPE".

    Args:
        vector_store (VectorStore): The vector store to use for generating the response.
        mode (str): The mode to use for generating the response.
        initial_prompt (str): The initial prompt to use for generating the response.
        sanitized_question (str): The sanitized version of the question to generate a response for.
        session_id (str): The session ID to use for retrieving the chat history when using the 'conversation_retrieval' chain type.

    Returns:
        str or dict: The generated response. If the response is a JSON string that can be parsed into a dictionary and contains
        the key 'text', the value of the 'text' key is returned after removing markdown code block formatting. If the response
        is a string, it is returned after removing markdown code block formatting.
    """

    # logger.debug(f"Entering get_completion_response function")
    # logger.debug(
    #     f"Mode: {mode}, Initial Prompt: {initial_prompt}, Sanitized Question: {sanitized_question}, Session ID: {session_id}"
    # )
    chain_type = os.getenv("CHAIN_TYPE", "conversation_retrieval")
    chain: QAWithSourcesChain
    if chain_type == "retrieval_qa":
        chain = getRetrievalQAWithSourcesChain(vector_store, mode, initial_prompt)
        response = chain.invoke(
            {"question": sanitized_question, "metadata": metadata},
            return_only_outputs=True,
        )
        response_text = response["answer"]
        logger.debug(f"RetrievalQA response: {response_text}")
    elif chain_type == "conversation_retrieval":
        chain = getConversationRetrievalChain(vector_store, mode, initial_prompt)
        logger.debug("getConversationRetrievalChain")
        chat_history = get_chat_history_for_retrieval_chain(
            session_id, limit=20, initial_prompt=initial_prompt
        )
        logger.debug(f"Formatted Chat_history {chat_history}")

        response = chain.invoke(
            {
                "question": sanitized_question,
                "chat_history": chat_history,
                "metadata": metadata,
            },
        )
        # Assuming 'response' is the JSON object you've provided
        source_documents = response["source_documents"]

        # Initialize an empty list to hold metadata from all documents
        all_metadata = []

        # Iterate through each document in the source documents
        for document in source_documents:
            # Correctly access the metadata attribute or method of the Document object
            # Assuming the Document object has a 'metadata' attribute
            metadata = document.metadata

            # Add the metadata dictionary to the list
            all_metadata.append(metadata)

        response_text = response.get("answer", "")

    try:
        # Attempt to parse the response_text as JSON
        response_text = json.loads(response_text)

    except json.JSONDecodeError:
        # If response_text is not a JSON string, leave it as is
        pass
    # Trim markdown code block formatting if present
    if isinstance(response_text, dict) and "text" in response_text:
        # Remove markdown code block formatting if present
        response_text["text"] = (
            response_text["text"].replace("```", "").replace("markdown\n", "").strip()
        )
        logger.debug(f"Response text after markdown removal: {response_text['text']}")
    elif isinstance(response_text, str):
        # Remove markdown code block formatting if present
        response_text = (
            response_text.replace("```", "").replace("markdown\n", "").strip()
        )
        logger.debug(f"Response text after markdown removal: {response_text}")
    # print(f"metadata {metadata}")
    return response_text, all_metadata
