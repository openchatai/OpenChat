from langchain.vectorstores.base import VectorStore
from dotenv import load_dotenv
from langchain.chains import RetrievalQA
from langchain.prompts import PromptTemplate
from api.utils.get_prompts import get_condense_prompt_by_mode, get_qa_prompt_by_mode
from api.utils.get_openai_llm import get_openai_model
from langchain import PromptTemplate, LLMChain

load_dotenv()

# https://python.langchain.com/docs/use_cases/question_answering/
def get_qa_chain(vector_store: VectorStore, mode, initial_prompt: str):
    
    llm = get_openai_model()

    template = get_qa_prompt_by_mode(mode, initial_prompt=initial_prompt)
    prompt = PromptTemplate.from_template(template)

    qa_chain = RetrievalQA.from_chain_type(
        llm,
        retriever=vector_store.as_retriever(),
        chain_type_kwargs={"prompt": prompt}
    )
    

    return qa_chain


def get_condense_chain(mode: str):
    llm = get_openai_model()
    template = get_condense_prompt_by_mode(mode)
    llm_chain = LLMChain.from_string(llm=llm, template=template)
    return llm_chain