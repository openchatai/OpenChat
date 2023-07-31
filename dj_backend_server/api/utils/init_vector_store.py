from langchain.docstore.document import Document
from langchain.vectorstores.pinecone import PineconeStore
from langchain.vectorstores.qdrant import Qdrant
from api.enums import StoreType
from langchain.embeddings.openai import OpenAIEmbeddings
from api.interfaces import StoreOptions
from api.configs import PINECONE_TEXT_KEY, VECTOR_STORE_INDEX_NAME
from .pinecone_client import PineconeSingleton

from dotenv import load_dotenv
import os

# Load environment variables from .env file
load_dotenv()

def init_vector_store(docs: list[Document], embeddings: OpenAIEmbeddings, options: StoreOptions) -> None:
    store_type = StoreType[os.environ['STORE']]

    if store_type == StoreType.PINECONE:
        pinecone = PineconeSingleton.get_instance()
        PineconeStore.from_documents(
            docs, embeddings, pinecone.Index(VECTOR_STORE_INDEX_NAME), options.namespace, PINECONE_TEXT_KEY
        )

    elif store_type == StoreType.QDRANT:
        Qdrant.from_documents(docs, embeddings, options.namespace, os.environ['QDRANT_URL'])

    else:
        valid_stores = ", ".join(StoreType._member_names())
        raise ValueError(f"Invalid STORE environment variable value: {os.environ['STORE']}. Valid values are: {valid_stores}")