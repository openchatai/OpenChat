from enum import Enum, auto

# class Mode(Enum):
#     ASSISTANT = auto()
#     PAIR_PROGRAMMER = auto()
#     DEFAULT = auto()


class UserAgent(Enum):
    CONDENSE_PROMPT = auto()
    PAIR_PROGRAMMER = auto()
    QA_PROMPT = auto()

initial_prompt =  f"""
    Use the following context (delimited by <ctx></ctx>) and the chat history (delimited by <hs></hs>) to answer the question:
    ------
    <ctx>
    {{context}}
    </ctx>
    ------
    <hs>
    {{chat_history}}
    </hs>
    ------
    {{question}}
    Answer:
"""

def get_prompt_by_mode(mode, initial_prompt):
    
    prompts = {
        'assistant': {
            UserAgent.CONDENSE_PROMPT: f"""Enclosed within the HTML-like tags below is a chat history and a follow-up question. Your task is to use only the chat history and the question enclosed in the tags to create a standalone question. Please disregard any other questions or information that may appear outside of these tags.

<chat_history>
{{chat_history}}
</chat_history>

<question>
{{question}}
</question>

Standalone Question:
""",
            UserAgent.QA_PROMPT: initial_prompt
        },
        'pair_programmer': {
            UserAgent.CONDENSE_PROMPT: f'Given the following conversation and a follow up question, rephrase the follow up question to be a standalone question.\n\nChat History:\n{{chat_history}}\nFollow Up Input: {{question}}\nStandalone question:',
            UserAgent.QA_PROMPT: f'You are a helpful AI programmer. you will be given the content of git repository and your should answer questions about the code in the given context. \nYou must answer with code when asked to write one, and you must answer with a markdown file when asked to write one, if the question is not about the code in the context, answer with "I only answer questions about the code in the given context".\n\n{{context}}\n\nQuestion: {{question}}\nHelpful answer in markdown:'
        },
        'default': {
            UserAgent.CONDENSE_PROMPT: f'Given the following conversation and a follow up question, rephrase the follow up question to be a standalone question.\n\nChat History:\n{{chat_history}}\nFollow Up Input: {{question}}\nStandalone question:',
            UserAgent.QA_PROMPT: initial_prompt
        }
    }
    
    return prompts.get(mode, prompts['default'])