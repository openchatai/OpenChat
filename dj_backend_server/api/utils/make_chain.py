from langchain.chains.conversational_retrieval.base import ConversationalRetrievalChain
from langchain.llms import OpenAI, OpenAIChat
from langchain.vectorstores.base import VectorStore

import os

def make_chain(vectorstore: VectorStore, mode: str, initial_prompt: str) -> ConversationalRetrievalChain:

    prompts = get_initial_prompt_by_mode(mode, initial_prompt)
    model = OpenAIChat() if os.environ['USE_AZURE_OPENAI'] else OpenAI()

    enable_source_documents = False
    if mode == 'pair_programmer':
        enable_source_documents = True

    return ConversationalRetrievalChain.from_llm(
        llm=model,
        retriever=vectorstore.as_retriever(),
        initial_prompt=prompts.qa_prompt,
        condense_question_prompt=prompts.condense_prompt,
        enable_source_documents=enable_source_documents
    )


def get_initial_prompt_by_mode(mode, initial_prompt, chat_history=None, question=None, context=None):
    default_condense_prompt = "Given the following conversation and a follow up question, rephrase the follow up question to be a standalone question.\n\nChat History:\n{chat_history}\nFollow Up Input: {question}\nStandalone question:"

    if mode == 'assistant':
        condense_prompt = default_condense_prompt
        qa_prompt = initial_prompt
    elif mode == 'pair_programmer':
        condense_prompt = default_condense_prompt
        qa_prompt = f"You are a helpful AI programmer. you will be given the content of git repository and your should answer questions about the code in the given context.\nYou must answer with code when asked to write one, and you must answer with a markdown file when asked to write one, if the question is not about the code in the context, answer with \"I only answer questions about the code in the given context\".\n\n{context}\n\nQuestion: {question}\nHelpful answer in markdown:"
    else:
        condense_prompt = default_condense_prompt
        qa_prompt = initial_prompt

    return {'condense_prompt': condense_prompt, 'qa_prompt': qa_prompt}
