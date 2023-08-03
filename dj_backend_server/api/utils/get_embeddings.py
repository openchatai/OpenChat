from langchain.embeddings.openai import OpenAIEmbeddings
from api.enums import EmbeddingProvider
import os
from dotenv import load_dotenv
from langchain.embeddings.base import Embeddings

load_dotenv()

# https://github.com/easonlai/azure_openai_langchain_sample/blob/main/chat_with_pdf.ipynb
import os


def get_embeddings() -> Embeddings:
    """Gets embeddings from the specified embedding type."""
    embedding_provider = os.environ.get("EMBEDDING_PROVIDER")
    print(embedding_provider)
    if embedding_provider == EmbeddingProvider.OPENAI.value:
        openai_api_key = os.environ.get("OPENAI_API_KEY")
        deployment = os.environ.get("OPENAI_EMBEDDING_MODEL_NAME")
        client = os.environ.get("OPENAI_API_TYPE")
        return OpenAIEmbeddings(openai_api_key=openai_api_key, deployment=deployment, client=client, chunk_size=8)
    else:
        available_providers = ", ".join([service.value for service in EmbeddingProvider])
        raise ValueError(f"Embedding service '{embedding_provider}' is not currently available. "
                         f"Available services: {available_providers}")

