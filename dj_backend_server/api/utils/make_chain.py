from dotenv import load_dotenv
import logging.config
import json
import io
from typing import Optional, Dict, Any, List
from django.conf import settings
from langchain.vectorstores.base import VectorStore
from langchain.chains import RetrievalQA
from langchain.prompts import PromptTemplate
from langchain.memory import ConversationBufferMemory
from langchain_openai.chat_models import ChatOpenAI
from langchain.chains import LLMChain
from langchain.chains import RetrievalQAWithSourcesChain, ConversationalRetrievalChain
from api.utils.get_prompts import get_qa_prompt_by_mode
from api.utils.get_openai_llm import get_llm


load_dotenv()
logging.config.dictConfig(settings.LOGGING)
logger = logging.getLogger(__name__)


def get_qa_chain(
    vector_store: VectorStore, mode: str, initial_prompt: str
) -> RetrievalQA:
    """
    This function creates a RetrievalQA object, which is used for question-answering tasks. It retrieves the language model and the
    question-answering prompt based on the mode and the initial prompt, and uses them along with the vector store to create the
    RetrievalQA object.

    Args:
        vector_store (VectorStore): The vector store, which is used to retrieve documents based on their vector representations.
        mode (str): The mode, which determines the question-answering prompt. The mode can be 'qa', 'qa_with_sources', or 'conversation'.
        initial_prompt (str): The initial prompt, which is used to generate the question-answering prompt.

    Returns:
        RetrievalQA: The RetrievalQA object, which can be used for question-answering tasks.
    """
    llm = get_llm()
    template = get_qa_prompt_by_mode(mode, initial_prompt=initial_prompt)
    prompt = PromptTemplate.from_template(template)

    qa_chain = RetrievalQA.from_chain_type(
        llm,
        retriever=vector_store.as_retriever(),
        chain_type_kwargs={"prompt": prompt},
        return_source_documents=True,
    )
    return qa_chain


def getRetrievalQAWithSourcesChain(
    vector_store: VectorStore, mode: str, initial_prompt: str
) -> RetrievalQAWithSourcesChain:
    """
    This function creates a RetrievalQAWithSourcesChain object, which is used for question-answering tasks. It retrieves the language model
    and uses it along with the vector store to create the RetrievalQAWithSourcesChain object.

    Args:
        vector_store (VectorStore): The vector store, which is used to retrieve documents based on their vector representations.
        mode (str): The mode, which determines the question-answering prompt. The mode can be 'qa', 'qa_with_sources', or 'conversation'.
        initial_prompt (str): The initial prompt, which is used to generate the question-answering prompt.

    Returns:
        RetrievalQAWithSourcesChain: The RetrievalQAWithSourcesChain object, which can be used for question-answering tasks.
    """
    llm = get_llm()
    chain = RetrievalQAWithSourcesChain.from_chain_type(
        llm, chain_type="stuff", retriever=vector_store.as_retriever()
    )
    logger.debug(f"ConversationalRetrievalChain created: {chain}")
    return chain


def getConversationRetrievalChain(
    vector_store: VectorStore,
    mode: str,
    initial_prompt: str,
    filters: Optional[Dict[str, Any]] = None,
) -> ConversationalRetrievalChain:
    """
    Get a conversation retrieval chain with optional dynamic filters.

    This function creates a ConversationalRetrievalChain object, which is used for conversational retrieval tasks. It retrieves the
    language model and the question-answering prompt based on the mode and the initial prompt, and uses them along with the vector store
    to create the ConversationalRetrievalChain object.

    Args:
        vector_store (VectorStore): The vector store, which is used to retrieve documents based on their vector representations.
        mode (str): The mode, which determines the question-answering prompt. The mode can be 'qa', 'qa_with_sources', or 'conversation'.
        initial_prompt (str): The initial prompt, which is used to generate the question-answering prompt.
        memory_key (str): The memory key.
        filters (Optional[Dict[str, Any]]): Optional filters as a dictionary of key-value pairs.

    Returns:
        ConversationalRetrievalChain: The ConversationalRetrievalChain object, which can be used for conversational retrieval tasks.

    Usage:
        chain = ConversationalRetrievalChain.from_llm(
            OpenAI(temperature=0),
            retriever=vector_store.as_retriever(
                search_kwargs={'filter': {'category': ['c1', 'c2', 'c3']}}
            ),
            return_source_documents=True
        )
    """

    llm = get_llm()
    template = get_qa_prompt_by_mode(mode, initial_prompt=initial_prompt)
    prompt = PromptTemplate.from_template(template)
    search_kwargs = {"filter": filters} if filters else {}
    chain = ConversationalRetrievalChain.from_llm(
        llm,
        chain_type="stuff",
        retriever=vector_store.as_retriever(search_kwargs=search_kwargs),
        verbose=True,
        combine_docs_chain_kwargs={"prompt": prompt},
        return_source_documents=True,
    )
    logger.debug(f"ConversationalRetrievalChain {llm}, created: {chain}")
    return chain


def process_text_with_llm(txt_file_path: str, mode, initial_prompt: str):
    """
    This function processes a text file or an in-memory text stream using a language model. It reads the text, creates a prompt
    template with the initial prompt, formats the prompt template with the text, sends the formatted prompt to the language model,
    and writes the response back into the text file or the in-memory text stream.

    Args:
        txt_file_path (str or io.StringIO): The path to the text file or an in-memory text stream containing the text to be processed.
        mode (str): The mode, which determines the question-answering prompt. It could be 'default', 'elaborative', or 'socratic'.
        initial_prompt (str): The initial prompt, which is used to generate the question-answering prompt.

    Returns:
        None. The function writes the response from the language model back into the text file or the in-memory text stream.
    """
    # Check if txt_file_path is an in-memory text stream
    if isinstance(txt_file_path, io.StringIO):
        text = txt_file_path.getvalue()
    else:
        # Read the text file
        with open(txt_file_path, "r") as txt_file:
            text = txt_file.read()

    # Send the formatted prompt to LLM and get the result
    llm = get_llm()
    result = llm.invoke(input=initial_prompt.format(text=text), temperature=0)

    # Extract the response from the result
    if hasattr(result, "content"):
        response = result.content
    else:
        print(
            f"Error: LLM result is not a dictionary or a string. It is a {type(result)} with value {result}"
        )
        return

    # Check if txt_file_path is a string or an in-memory text stream
    if isinstance(txt_file_path, io.StringIO):
        # Write the response back into the in-memory text stream
        txt_file_path.write(response)
        print(f"Write with value {txt_file_path}")
    else:
        # Write the response into a new text file
        result_file_path = txt_file_path.replace(".txt", "_processed.txt")
        result_file_path = txt_file_path.replace(".txt", ".txt")
        with open(result_file_path, "w") as result_file:
            result_file.write(response)
            print(f"Write with value {result_file_path}")
