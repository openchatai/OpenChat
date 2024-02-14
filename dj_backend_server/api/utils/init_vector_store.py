from langchain.docstore.document import Document
from langchain.vectorstores.qdrant import Qdrant
from langchain.embeddings.openai import OpenAIEmbeddings
from langchain.vectorstores.pinecone import Pinecone
from qdrant_client import QdrantClient
from qdrant_client import models
from api.enums import StoreType
from api.interfaces import StoreOptions
from api.configs import PINECONE_TEXT_KEY, VECTOR_STORE_INDEX_NAME
import pinecone
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
    """
    This function initializes a vector store based on the 'STORE' environment variable. If the 'STORE' environment variable is 
    'PINECONE', it initializes Pinecone and uses the Pinecone vector store. If the 'STORE' environment variable is 'QDRANT', it uses 
    the Qdrant vector store. If the 'STORE' environment variable is neither 'PINECONE' nor 'QDRANT', it raises a ValueError.

    Args:
        docs (list[Document]): A list of Document objects to be stored in the vector store.
        embeddings (OpenAIEmbeddings): The OpenAI embeddings to be used for the documents.
        options (StoreOptions): The options for the vector store, including the namespace.

    Raises:
        ValueError: If the 'STORE' environment variable is neither 'PINECONE' nor 'QDRANT'.
    """
    store_type = StoreType[os.environ['STORE']]

    if store_type == StoreType.PINECONE:
        initialize_pinecone()

        # Use the Pinecone vector store
        # docs, embeddings, VECTOR_STORE_INDEX_NAME, options.namespace, PINECONE_TEXT_KEY
        Pinecone.from_documents(documents=docs, embedding=embeddings, index_name=VECTOR_STORE_INDEX_NAME, namespace=options.namespace)

    elif store_type == StoreType.QDRANT:
        # print("Called qdrant.from_documents")
        Qdrant.from_documents(docs, embeddings, collection_name=options.namespace, url=os.environ['QDRANT_URL'])

    else:
        valid_stores = ", ".join(StoreType._member_names())
        raise ValueError(f"Invalid STORE environment variable value: {os.environ['STORE']}. Valid values are: {valid_stores}")
    
def delete_from_vector_store(namespace: str, filter_criteria: dict) -> None: 
    """
    This function deletes records from a vector store based on the 'STORE' environment variable and the provided filter criteria. 
    If the 'STORE' environment variable is 'QDRANT', it deletes records from the Qdrant vector store. If the 'STORE' environment 
    variable is not 'QDRANT', it raises a NotImplementedError.

    Args:
        namespace (str): The namespace of the vector store from which records are to be deleted.
        filter_criteria (dict): The criteria to filter the records to be deleted. The criteria should include a 'source' key with 
        a string value that ends with '.txt'.

    Raises:
        NotImplementedError: If the 'STORE' environment variable is not 'QDRANT'.
    """
    store_type = StoreType[os.environ['STORE']]

    if store_type == StoreType.QDRANT:
        # Extract host and port from QDRANT_URL
        qdrant_url = os.environ['QDRANT_URL']
        url_parts = qdrant_url.split(':')
        host = url_parts[1].replace('//', '')
        port = int(url_parts[2])
        # Initialize the Qdrant client
        qdrant_client = QdrantClient(host=host, port=port)

        source_value = str(filter_criteria.get("source", "")).lower()
        if not source_value.endswith('.txt'):
            for ext in ['.pdf', '.docx', '.doc', '.json', '.xls', '.csv', '.xlsx']:
                if source_value.endswith(ext):
                    source_value = source_value[:-len(ext)] + '.txt'
                    break

        if source_value:
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


def ensure_vector_database_exists(namespace):
    store_type = StoreType[os.environ['STORE']]
    try:
        if store_type == StoreType.QDRANT:
            client = QdrantClient(url=os.environ['QDRANT_URL'])
            for attempt in range(3):
                existing_collections = client.get_collections().collections
                if namespace not in existing_collections:
                    print(f"Namespace '{namespace}' does not exist. Attempting to create.")
                    vectors_config = models.VectorParams(
                        size=1536,  # Using 1536-dimensional vectors, adjust as necessary
                        distance=models.Distance.COSINE  # Using cosine distance, adjust as necessary
                    )
                    client.create_collection(collection_name=namespace, vectors_config=vectors_config)
                    # Recheck if the namespace was successfully created
                    if namespace in client.get_collections().collections:
                        print(f"Namespace '{namespace}' successfully created.")
                        return
                    else:
                        print(f"Failed to create namespace '{namespace}' on attempt {attempt + 1}.")
                else:
                    print(f"Namespace '{namespace}' exists.")
                    return
            raise Exception(f"Failed to ensure or create namespace '{namespace}' after 3 attempts.")
    except Exception as e:
        print(f"Failed to ensure vector database exists for namespace {namespace}: {e}")

