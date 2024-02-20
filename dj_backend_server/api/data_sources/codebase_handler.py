# views.py
import logging.config
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from django.conf import settings
from api.utils import init_vector_store
from api.utils import get_embeddings
from api.interfaces import StoreOptions
from langchain_community.document_loaders import GitLoader
from langchain.text_splitter import RecursiveCharacterTextSplitter
from web.models.codebase_data_sources import CodebaseDataSource
from typing import Optional, Dict, Any, List

logging.config.dictConfig(settings.LOGGING)
logger = logging.getLogger(__name__)


# https://python.langchain.com/docs/integrations/document_loaders/git
@csrf_exempt
def codebase_handler(repo_path: str, namespace: str, metadata: Dict[str, Any]):
    try:
        folder_path = f"website_data_sources/{namespace}"
        loader = GitLoader(repo_path=folder_path, clone_url=repo_path, branch="master")

        raw_docs = loader.load()
        logging.debug("Loaded documents")
        for doc in raw_docs:
            doc.metadata = (
                getattr(doc, "metadata", {})
                if getattr(doc, "metadata", {}) is not None
                else {}
            )

        text_splitter = RecursiveCharacterTextSplitter(
            separators=["\n"], chunk_size=1000, chunk_overlap=200, length_function=len
        )
        docs = text_splitter.split_documents(raw_docs)

        embeddings = get_embeddings()

        init_vector_store(
            docs,
            embeddings,
            options=StoreOptions(namespace),
            metadata={
                "bot_id": str(CodebaseDataSource.chatbot.id),
                "repository": str(CodebaseDataSource.chatbot.id),
                "last_update": CodebaseDataSource.ingested_at.strftime(
                    "%Y-%m-%d %H:%M:%S"
                ),
                "type": "codebase",
            },
        )

        print("Indexed documents. all done!")
    except Exception as e:
        print(e)
