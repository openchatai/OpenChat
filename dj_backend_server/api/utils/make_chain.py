from langchain.chains.conversational_retrieval.base import ConversationalRetrievalChain
# Import Azure OpenAI
from langchain.llms import AzureOpenAI
from langchain.vectorstores.base import VectorStore

from dotenv import load_dotenv
from langchain.chains import ConversationalRetrievalChain
from langchain.prompts import SystemMessagePromptTemplate
from langchain.memory import VectorStoreRetrieverMemory

load_dotenv()
import os


# https://python.langchain.com/docs/modules/memory/
def make_chain(vector_store: VectorStore, mode: str, initial_prompt: str) -> ConversationalRetrievalChain:
    # https://github.com/easonlai/azure_openai_langchain_sample/blob/main/chat_with_pdf.ipynb
    llm = AzureOpenAI(
        openai_api_key=os.environ['OPENAI_API_KEY'], 
        deployment_name=os.environ['OPENAI_DEPLOYMENT_NAME'], 
        model_name=os.environ['OPENAI_COMPLETION_MODEL'],
        max_tokens=1000
    )

    memory = VectorStoreRetrieverMemory(retriever=vector_store.as_retriever(), memory_key="chat_history", return_docs=False, return_messages=True)

    qachat = ConversationalRetrievalChain.from_llm(llm, retriever=vector_store.as_retriever(), return_source_documents=True, get_chat_history=lambda h : h, chain_type="stuff")    
    return qachat