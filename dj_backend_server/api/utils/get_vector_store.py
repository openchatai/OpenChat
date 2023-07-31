import os
from langchain.embeddings import OpenAIEmbeddings
from langchain.vectorstores.pinecone import Pinecone
from langchain.vectorstores.qdrant import Qdrant
from langchain.vectorstores import VectorStore
from api.enums import StoreType
from api.configs import VECTOR_STORE_INDEX_NAME, PINECONE_TEXT_KEY
from api.interfaces import StoreOptions

def get_vector_store(options: StoreOptions):
  """Gets the vector store for the given options."""
  vector_store: VectorStore = None

  store_type = os.environ.get('STORE')
  if store_type == StoreType.PINECONE:
    vector_store = Pinecone.from_existing_index(
        embedding=OpenAIEmbeddings(),
        index_name=VECTOR_STORE_INDEX_NAME,
        text_key=PINECONE_TEXT_KEY,
        namespace=options.namespace
    )
  elif store_type == StoreType.QDRANT:
    vector_store = Qdrant.from_existing_collection(
        OpenAIEmbeddings(),
        {
          'collectionName': options.namespace,
          'url': os.environ.get('QDRANT_URL'),
        },
    )
  else:
    raise ValueError('Invalid STORE environment variable value')

  return vector_store
