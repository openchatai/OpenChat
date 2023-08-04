import os
from langchain.vectorstores.pinecone import Pinecone

from langchain.vectorstores.qdrant import Qdrant
from langchain.vectorstores import VectorStore
from api.enums import StoreType
from api.configs import VECTOR_STORE_INDEX_NAME, PINECONE_TEXT_KEY
from api.interfaces import StoreOptions
from dotenv import load_dotenv
from api.utils.get_embeddings import get_embeddings
from api.utils.init_vector_store import initialize_pinecone
import qdrant_client


load_dotenv()

def get_vector_store(options: StoreOptions) -> VectorStore:
  """Gets the vector store for the given options."""
  vector_store: VectorStore = None
  embedding = get_embeddings()

  store_type = os.environ.get('STORE')
  if store_type == StoreType.PINECONE.value:
    initialize_pinecone()
    vector_store = Pinecone.from_existing_index(
        VECTOR_STORE_INDEX_NAME,
        embedding,
        PINECONE_TEXT_KEY,
        options.namespace
    )
  elif store_type == StoreType.QDRANT.value:
    client = qdrant_client.QdrantClient(url=os.environ['QDRANT_URL'], prefer_grpc=True)
    vector_store = Qdrant(client, collection_name=options.namespace, embeddings=embedding)
    # vector_store = Qdrant.from_documents([], embedding, url='http://localhost:6333', collection=options.namespace)

  else:
    raise ValueError('Invalid STORE environment variable value')

  return vector_store
