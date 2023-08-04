from langchain.docstore.document import Document
from langchain.vectorstores.qdrant import Qdrant
from api.enums import StoreType
from langchain.embeddings.openai import OpenAIEmbeddings
from api.interfaces import StoreOptions
from api.configs import PINECONE_TEXT_KEY, VECTOR_STORE_INDEX_NAME
import pinecone
from langchain.vectorstores.pinecone import Pinecone
from dotenv import load_dotenv
import os
import threading
init_lock = threading.Lock()

# Load environment variables from .env file
load_dotenv()

initialized = False
def initialize_pinecone():
    global initialized
    # Only initialize Pinecone if the store type is Pinecone and the initialization lock is not acquired
    with init_lock:
        if not initialized:
            # Initialize Pinecone
            pinecone.init(
                api_key=os.getenv("PINECONE_API_KEY"),  # find at app.pinecone.io
                environment=os.getenv("PINECONE_ENV"),  # next to api key in console
            )
            initialized = True

def init_vector_store(docs: list[Document], embeddings: OpenAIEmbeddings, options: StoreOptions) -> None:
    store_type = StoreType[os.environ['STORE']]

    if store_type == StoreType.PINECONE:
        initialize_pinecone()

        # Use the Pinecone vector store
        # docs, embeddings, VECTOR_STORE_INDEX_NAME, options.namespace, PINECONE_TEXT_KEY
        Pinecone.from_documents(documents=docs, embedding=embeddings, index_name=VECTOR_STORE_INDEX_NAME, namespace=options.namespace)

    elif store_type == StoreType.QDRANT:
        print("called qdrant.from_documents")
        Qdrant.from_documents(docs, embeddings, collection_name=options.namespace, url=os.environ['QDRANT_URL'])

    else:
        valid_stores = ", ".join(StoreType._member_names())
        raise ValueError(f"Invalid STORE environment variable value: {os.environ['STORE']}. Valid values are: {valid_stores}")