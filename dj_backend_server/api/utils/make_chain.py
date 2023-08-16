from langchain.vectorstores.base import VectorStore
from dotenv import load_dotenv
from langchain.chains import RetrievalQA
from langchain.prompts import PromptTemplate
from langchain.memory import ConversationBufferMemory
from api.utils.get_openai_llm import get_llm
from langchain import PromptTemplate, LLMChain
from langchain.chains import RetrievalQAWithSourcesChain, ConversationalRetrievalChain
from api.utils.get_prompts import get_qa_prompt_by_mode
from typing import Optional, Dict, Any, List

load_dotenv()

def get_qa_chain(vector_store: VectorStore, mode, initial_prompt: str) -> RetrievalQA:
    llm = get_llm()
    template = get_qa_prompt_by_mode(mode, initial_prompt=initial_prompt)
    prompt = PromptTemplate.from_template(template)

    qa_chain = RetrievalQA.from_chain_type(
        llm,
        retriever=vector_store.as_retriever(),
        chain_type_kwargs={"prompt": prompt},
        return_source_documents=True
    )
    

    return qa_chain
def getRetrievalQAWithSourcesChain(vector_store: VectorStore, mode, initial_prompt: str):
    llm = get_llm()
    chain = RetrievalQAWithSourcesChain.from_chain_type(llm, chain_type="stuff", retriever=vector_store.as_retriever())
    return chain


def getConversationRetrievalChain(
    vector_store: VectorStore,
    mode: str,
    initial_prompt: str,
    filters: Optional[Dict[str, Any]] = None
) -> ConversationalRetrievalChain:
    """
    Get a conversation retrieval chain with optional dynamic filters.

    Args:
        vector_store (VectorStore): The vector store for retrieval.
        mode (str): The mode of the conversation.
        initial_prompt (str): The initial prompt for the conversation.
        memory_key (str): The memory key.
        filters (Optional[Dict[str, Any]]): Optional filters as a dictionary of key-value pairs.

    Returns:
        ConversationalRetrievalChain: The generated conversation retrieval chain.
        
    Usage:
        chain = ConversationalRetrievalChain.from_llm(
            OpenAI(temperature=0),
            retriever=vectorstore.as_retriever(
                search_kwargs={'filter': {'category': ['c1', 'c2', 'c3']}}
            ),
            memory=memory,
            return_source_documents=True
        )
    """
    filter_config = None
    if filters:
        filter_dict = {"filter": filters}
        filter_config = {"search_kwargs": filter_dict}
    
    llm = get_llm()
    template = get_qa_prompt_by_mode(mode, initial_prompt=initial_prompt)
    prompt = PromptTemplate.from_template(template)
    chain = ConversationalRetrievalChain.from_llm(
        llm, 
        chain_type="stuff",
        retriever=vector_store.as_retriever(),
        verbose=True,
        combine_docs_chain_kwargs={"prompt": prompt},
        filter=filter_config  # Apply the filter if it exists
    )
    return chain