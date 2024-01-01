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
import threading, np
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
        print("called qdrant.from_documents")
        Qdrant.from_documents(docs, embeddings, collection_name=options.namespace, url=os.environ['QDRANT_URL'])

    else:
        valid_stores = ", ".join(StoreType._member_names())
        raise ValueError(f"Invalid STORE environment variable value: {os.environ['STORE']}. Valid values are: {valid_stores}")
    
def delete_from_vector_store(namespace: str, filter_criteria: dict) -> None:
    store_type = StoreType[os.environ['STORE']]

    if store_type == StoreType.QDRANT:
        # Extract host and port from QDRANT_URL
        qdrant_url = os.environ['QDRANT_URL']
        url_parts = qdrant_url.split(':')
        host = "//qdrant"
        port = "6333"
        # Initialize the Qdrant client
        qdrant_client = QdrantClient(host=host, port=port)
        # qdrant_client = QdrantClient(host, port=port)

        # Initialize the Qdrant vector store with the client and dummy embeddings
        dummy_embeddings = DummyEmbeddings()

        qdrant_store = Qdrant(qdrant_client, namespace, dummy_embeddings)
        # qdrant_store = Qdrant(client=qdrant_client, namespace=namespace, embeddings=dummy_embeddings)
        file_paths = filter_criteria["source"]
        if isinstance(file_paths, list):
            for file_path in file_paths:
                file_path_without_extension, _ = os.path.splitext(file_path)
                file_paths_to_search = [file_path_without_extension + '.pdf', file_path_without_extension + '.txt']
                for file_path_with_extension in file_paths_to_search:
                    # Constructing the filter for Qdrant search
                    search_filter = models.Filter(
                        must=[models.FieldCondition(
                            key="source",
                            match=models.Match(keyword=file_path_with_extension)
                        )]
                    )
                    # Perform the search
                    search_results = qdrant_store.search(query=dummy_embeddings.embed(),
                                                         collection_name=namespace,
                                                         search_filter=search_filter,
                                                         search_type="similarity",
                                                         top=1, limit=1) # Adjust the number based on expected results
                    if search_results:
                        for result in search_results:
                            point_id_to_delete = result.id
                            qdrant_store.delete_point(point_id_to_delete)
                            print(f"Deleted point with ID {point_id_to_delete} from collection '{namespace}'.")
                            break  # Stop searching if we found and deleted the point
                    else:
                        print(f"No points found for the file path {file_path_with_extension}.")

    else:
        raise NotImplementedError(f"Delete operation is not implemented for the store type: {store_type}")
    
class DummyEmbeddings:
    def __init__(self):
        self.vector_length = 2048  # Adjust as necessary

    def embed(self):
        return [0.2, 0.1, 0.9, 0.7] * (self.vector_length // 4)  # Return a dummy vector of the correct length
