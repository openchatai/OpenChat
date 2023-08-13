from typing import Dict, Optional

def get_qa_prompt_by_mode(
    mode: str, initial_prompt: Optional[str]
) -> str:

    qa_prompts = {
        "assistant": initial_prompt,
        "pair_programmer": """You are a helpful AI programmer. you will be given the content of git repository and your should answer questions about the code in the given context.
    You must answer with code when asked to write one, and you must answer with a markdown file when asked to write one, if the question is not about the code in the context, answer with "I only answer questions about the code in the given context".
    
    {context}
    
    Question: {question}
    Helpful answer in markdown:""",
    }

    if mode in qa_prompts:
        return qa_prompts[mode]

    return initial_prompt if initial_prompt else ""


def get_condense_prompt_by_mode(mode: str) -> str:

    condense_prompts = {
        "assistant": """Given the following conversation and a follow up question, rephrase the follow up question to be a standalone question.

    Chat History:
    {chat_history}
    Follow Up Input: {question}
    Standalone question:""",

        "pair_programmer": """Given the following conversation and a follow up question, rephrase the follow up question to be a standalone question.  

    Chat History:
    {chat_history}
    Follow Up Input: {question}
    Standalone question:""",
    }

    if mode in condense_prompts:
        return condense_prompts[mode]

    return """Given the following conversation and a follow up question, rephrase the follow up question to be a standalone question.

    Chat History:
    {chat_history} 
    Follow Up Input: {question}
    Standalone question:"""