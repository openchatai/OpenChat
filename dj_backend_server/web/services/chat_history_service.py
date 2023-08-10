from typing import List, Optional, Tuple
from web.models.chat_histories import ChatHistory

def get_chat_history_for_retrieval_chain(session_id: str, limit: Optional[int] = None) -> List[Tuple[str, str]]:
    """Fetches limited ChatHistory entries by session ID and converts to chat_history format.

    Args:
        session_id (str): The session ID to fetch chat history for 
        limit (int, optional): Maximum number of entries to retrieve

    Returns:
        list[tuple[str, str]]: List of tuples of (user_query, bot_response) 
    """
    
    # Query and limit results if a limit is provided
    query = ChatHistory.objects.filter(session_id=session_id).order_by('created_at')
    if limit:
        query = query[:limit]
        
    chat_history = []

    user_query = None
    for entry in query:
        if entry.from_user:
            user_query = entry.message
        else:
            if user_query is not None:
                chat_history.append((user_query, entry.message))
                user_query = None

    return chat_history