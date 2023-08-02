## To delete all older migrations
find . -path "*/migrations/*.py" -not -name "__init__.py" -delete
find . -path "*/migrations/*.pyc" -delete

## To create migrations for models [run the following from root directory]
> python manage.py makemigrations api


# Generate translations
> for web app
python manage.py makemessages -l en -i "web/*" -e html,py,js,txt
python manage.py compilemessages

> for both apps
python manage.py makemessages -l en -i "web/*" -i "api/*" -e html,py,js,txt
python manage.py compilemessages



## Langchain References
https://github.com/easonlai/azure_openai_langchain_sample/blob/main/chat_with_pdf.ipynb


## Also here
https://github.com/openai/openai-cookbook/blob/main/examples/vector_databases/qdrant/QA_with_Langchain_Qdrant_and_OpenAI.ipynb


---
Make Chain
---
```py
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

def make_chain(vector_store: VectorStore, mode: str, initial_prompt: str) -> ConversationalRetrievalChain:
    # https://github.com/easonlai/azure_openai_langchain_sample/blob/main/chat_with_pdf.ipynb
    llm = AzureOpenAI(
        openai_api_key=os.environ['OPENAI_API_KEY'], 
        deployment_name=os.environ['OPENAI_DEPLOYMENT_NAME'], 
        model_name=os.environ['OPENAI_COMPLETION_MODEL']
    )

    memory = VectorStoreRetrieverMemory(retriever=vector_store.as_retriever(), memory_key="chat_history", return_docs=False, return_messages=True)

    qachat = ConversationalRetrievalChain.from_llm(llm, retriever=vector_store.as_retriever(), memory=memory, return_source_documents=True, get_chat_history=lambda h : h)

    # MEMORY 👇
    chat_history = []

    ## Question 1
    query = "Hi there, how are you?"
    result = qachat({"question": query, "chat_history": chat_history})
    print(result['answer'])
    
    
    return qachat


```

---
Better Question answering
https://python.langchain.com/docs/use_cases/question_answering/how_to/chat_vector_db