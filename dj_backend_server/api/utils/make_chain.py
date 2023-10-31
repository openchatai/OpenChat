from langchain.vectorstores.base import VectorStore
from dotenv import load_dotenv
from langchain.chains import RetrievalQA
from langchain.prompts import PromptTemplate
from langchain.memory import ConversationBufferMemory
from api.utils.get_openai_llm import get_llm
from langchain import PromptTemplate, LLMChain
from langchain.chains import RetrievalQAWithSourcesChain, ConversationalRetrievalChain
from api.utils.get_prompts import get_qa_prompt_by_mode
import io

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


def getConversationRetrievalChain(vector_store: VectorStore, mode, initial_prompt: str):
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
    print(chain)
    return chain

def process_text_with_llm(txt_file_path: str, mode, initial_prompt: str):
    # Check if txt_file_path is an in-memory text stream
    if isinstance(txt_file_path, io.StringIO):
        text = txt_file_path.getvalue()
    else:
        # Read the text file
        with open(txt_file_path, 'r') as txt_file:
            text = txt_file.read()

    # Create a prompt template with your initial_prompt
    prompt_template = PromptTemplate.from_template(initial_prompt)

    # Format the prompt template with the text to be corrected
    formatted_prompt = prompt_template.format(text=text)

    # Send the formatted prompt to LLM and get the result
    llm = get_llm()
    print(f"Sending to LLM: {text}")
    result = llm(text)
    print(f"Results  LLM: {result}")

    # Check if result is a string
    if isinstance(result, str):
        response = result
    elif isinstance(result, dict):
        # Extract only the response from the result
        response = result['choices'][0]['message']['content']
    else:
        print(f"Error: LLM result is not a dictionary or a string. It is a {type(result)} with value {result}")
        return

    # Check if txt_file_path is a string or an in-memory text stream
    if isinstance(txt_file_path, io.StringIO):
        # Write the response back into the in-memory text stream
        txt_file_path.write(response)
        print(f"Write.  with value {txt_file_path}")
    else:
        # Write the response into a new text file
        result_file_path = txt_file_path.replace('.txt', '.txt')
        with open(result_file_path, 'w') as result_file:
            result_file.write(response)
            print(f"Write.  with value {result_file_path}")