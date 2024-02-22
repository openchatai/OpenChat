from typing import List, Optional, Tuple, NamedTuple
from web.models.chat_histories import ChatHistory
from langchain.schema import BaseMessage, AIMessage, HumanMessage
from langchain.memory import ConversationSummaryBufferMemory
from django.conf import settings
from api.utils.get_openai_llm import get_llm
import logging

logging.config.dictConfig(settings.LOGGING)
logger = logging.getLogger(__name__)


from langchain.schema import HumanMessage, AIMessage


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

    chat_history = []
    user_query = None
    llm = get_llm()
    # Now, chat_history is properly defined and can be used to initialize the memory
    memory = ConversationSummaryBufferMemory(
        llm=llm,
        max_token_limit=1024,
        memory_key=session_id,
        return_messages=True,
    )
    # Assuming chat_history is meant to be a list of messages for the memory
    # Here you should convert your query results into the desired format for chat_history
    # For example, appending dicts to chat_history list as shown below might be what you intended
    for entry in query:
        if entry.from_user == "True":
            chat_history.append(HumanMessage(content=entry.message))
            user_query = entry.message
        else:
            chat_history.append(AIMessage(content=entry.message))
            if user_query is not None:
                memory.save_context({"input": user_query}, {"output": entry.message})
                user_query = None

    # logger.debug(f"Memory PRINT: {memory}")
    # chat_history = memory.load_memory_variables({})
    return chat_history
