from typing import List, Optional, Tuple, NamedTuple
from web.models.chat_histories import ChatHistory
from langchain.schema import BaseMessage, AIMessage, HumanMessage
from django.conf import settings
import logging

logging.config.dictConfig(settings.LOGGING)
logger = logging.getLogger(__name__)


def get_chat_history_for_retrieval_chain(
    session_id: str, limit: Optional[int] = None, initial_prompt: Optional[str] = None
) -> List[dict]:
    """Fetches limited ChatHistory entries by session ID and converts to chat_history format.

    Args:
        session_id (str): The session ID to fetch chat history for
        limit (int, optional): Maximum number of entries to retrieve

    Returns:
        list[tuple[str, str]]: List of tuples of (user_query, bot_response)
    """

    # Query and limit results if a limit is provided
    query = ChatHistory.objects.filter(session_id=session_id).order_by("created_at")
    if limit:
        query = query[:limit]
    for entry in query:
        role = "user" if entry.from_user == "True" else "assistant"
        logger.debug(f"Chat history entry: {entry}, role: {role}")

    chat_history = [
        {
            "role": "system",
            "content": "This is an initial system message setting up the context.",
        }
    ]
    user_query = None

    # Directly interpret the from_user flag to assign roles correctly
    for entry in query:
        if entry.from_user == "True":
            # This entry is a user query; store it to pair with the next bot response
            user_query = entry.message
        else:
            # This entry is a bot response; pair it with the last user query if available
            if user_query is not None:
                chat_history.append({"role": "user", "content": user_query})
                chat_history.append({"role": "assistant", "content": entry.message})
                user_query = None

    return chat_history
