from langchain.docstore.document import Document
from langchain.vectorstores.qdrant import Qdrant
from qdrant_client import QdrantClient
from qdrant_client import models
from api.enums import StoreType
from langchain.embeddings.openai import OpenAIEmbeddings
from api.interfaces import StoreOptions
from api.configs import PINECONE_TEXT_KEY, VECTOR_STORE_INDEX_NAME
import pinecone
from langchain.vectorstores.pinecone import Pinecone
from dotenv import load_dotenv
import os
import threading
from urllib.parse import urlparse
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
        # print("LEHEL called qdrant.from_documents")
        Qdrant.from_documents(docs, embeddings, collection_name=options.namespace, url=os.environ['QDRANT_URL'])

    else:
        valid_stores = ", ".join(StoreType._member_names())
        raise ValueError(f"Invalid STORE environment variable value: {os.environ['STORE']}. Valid values are: {valid_stores}")
    
def delete_from_vector_store(namespace: str, filter_criteria: dict) -> None: # @TODO - not finished, need to find out some way to delete from qdrant
    store_type = StoreType[os.environ['STORE']]

    if store_type == StoreType.QDRANT:
        # Extract host and port from QDRANT_URL
        qdrant_url = os.environ['QDRANT_URL']
        url_parts = qdrant_url.split(':')
        host = url_parts[1].replace('//', '')
        port = int(url_parts[2])
        # Initialize the Qdrant client
        qdrant_client = QdrantClient(host=host, port=port)
        # qdrant_client = QdrantClient(host, port=port)

        # Initialize the Qdrant vector store with the client and dummy embeddings
        dummy_embeddings = DummyEmbeddings()
        qdrant_store = Qdrant(qdrant_client, namespace, dummy_embeddings)

        source_value = str(filter_criteria.get("source", "")).replace('.pdf', '.txt')  

        if source_value:
            # scroll_response = qdrant_client.scroll(
            records, point_ids = qdrant_client.scroll(
                collection_name=namespace,
                scroll_filter=models.Filter(
                    must=[
                        models.FieldCondition(
                            key="metadata.source",
                            match=models.MatchValue(value=str(source_value)),
                        ),
                    ]
                ),
                limit=int(100),
                offset=int(0),
                with_payload=True,
                with_vectors=False,
            )

            if records:
                point_ids = [record.id for record in records]
                qdrant_client.delete(
                    collection_name=namespace,
                    points_selector=models.PointIdsList(
                        points=point_ids,
                    ),
                )
                print(f"Deleted points with IDs {point_ids} from collection '{namespace}'.")
            else:
                print(f"No points found for the source '{source_value}'")

        else:
              print(f"No source value provided for deletion in collection '{namespace}'.")

    else:
        raise NotImplementedError(f"Delete operation is not implemented for the store type: {store_type}")
    
class DummyEmbeddings:
    def __init__(self):
        self.vector_length = 2048  # Adjust as necessary

    def embed(self):
        return [0.2, 0.1, 0.9, 0.7] * (self.vector_length // 4)  # Return a dummy vector of the correct length
