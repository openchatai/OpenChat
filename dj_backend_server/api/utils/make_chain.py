from langchain.vectorstores.base import VectorStore
from dotenv import load_dotenv
from langchain.chains import RetrievalQA
from langchain.prompts import PromptTemplate
from langchain.memory import ConversationBufferMemory
from api.utils.get_openai_llm import get_llm
from langchain import PromptTemplate, LLMChain
from langchain.chains import RetrievalQAWithSourcesChain, ConversationalRetrievalChain
from api.utils.get_prompts import get_qa_prompt_by_mode

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


def getConversationRetrievalChain(vector_store: VectorStore, mode, initial_prompt: str, memory_key: str):
    llm = get_llm()
    template = get_qa_prompt_by_mode(mode, initial_prompt=initial_prompt)
    prompt = PromptTemplate.from_template(template)
    chain = ConversationalRetrievalChain.from_llm(
        llm, 
        chain_type="stuff", 
        retriever=vector_store.as_retriever(), 
        verbose=True,
        combine_docs_chain_kwargs={"prompt": prompt}
    )
    return chain