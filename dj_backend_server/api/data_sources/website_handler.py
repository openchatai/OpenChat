import os
import traceback
from datetime import datetime
from uuid import uuid4
from typing import Optional, Dict, Any, List
import logging.config
from django.utils.timezone import now
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from django.conf import settings
from langchain_community.document_loaders.directory import DirectoryLoader
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain_community.document_loaders import TextLoader
from api.utils import init_vector_store
from api.utils.get_embeddings import get_embeddings
from api.interfaces import StoreOptions
from web.models.website_data_sources import WebsiteDataSource
from web.models.crawled_pages import CrawledPages
from web.enums.website_data_source_status_enum import WebsiteDataSourceStatusType
from web.models.failed_jobs import FailedJob

logging.config.dictConfig(settings.LOGGING)
logger = logging.getLogger(__name__)


@csrf_exempt
def website_handler(shared_folder, namespace, metadata: Dict[str, Any]):
    """
    This function handles the processing of text files in a shared folder. It loads the text files, splits the text into chunks,
    generates embeddings for the chunks, and initializes a vector store with the chunks and their embeddings. If the processing is
    successful, it updates the status of the website data source to 'COMPLETED'. If an exception occurs during the processing, it
    updates the status of the website data source to 'FAILED' and saves the exception details in a FailedJob object.

    Args:
        shared_folder (str): The ID of the WebsiteDataSource object and the name of the shared folder where the text files are located.
        namespace (str): The namespace for the vector store.

    Raises:
        Exception: If an error occurs during the processing of the text files or the initialization of the vector store.
    """


def website_handler(shared_folder, namespace, metadata: Dict[str, Any]):
    website_data_source = WebsiteDataSource.objects.get(id=shared_folder)
    try:
        directory_path = os.path.join("website_data_sources", shared_folder)
        directory_loader = DirectoryLoader(
            directory_path,
            glob="**/*.txt",
            loader_cls=TextLoader,
            use_multithreading=True,
        )

        raw_docs = directory_loader.load()

        for doc in raw_docs:
            doc_metadata = (
                getattr(doc, "metadata", {})
                if getattr(doc, "metadata", {}) is not None
                else {}
            )
            metadata = metadata if metadata is not None else {}
            doc_metadata.update(metadata)
            setattr(doc, "metadata", doc_metadata)

        text_splitter = RecursiveCharacterTextSplitter(
            chunk_size=1000, chunk_overlap=200, length_function=len
        )

        docs = text_splitter.split_documents(raw_docs)

        logging.debug("website docs --> %s", docs)
        embeddings = get_embeddings()
        crawled_pages = CrawledPages.objects.filter(
            website_data_source=website_data_source
        )
        links_titles = [
            {"link": page.url, "title": page.title} for page in crawled_pages
        ]

        init_vector_store(
            docs,
            embeddings,
            StoreOptions(namespace=namespace),
            metadata={
                "bot_id": str(website_data_source.chatbot_id),
                "last_update": website_data_source.updated_at.strftime("%Y-%m-%d %H:%M:%S"),
                "type": "website",
                "link": links_titles[0]["link"] if links_titles else None,
                "title": links_titles[0]["title"] if links_titles else "Untitled Page",
            },
        )

        website_data_source.crawling_status = (
            WebsiteDataSourceStatusType.COMPLETED.value
        )
        website_data_source.save()
        logging.debug("Website embeddings, done ...")
    except Exception as e:
        website_data_source.crawling_status = WebsiteDataSourceStatusType.FAILED.value
        website_data_source.save()

        failed_job = FailedJob(
            uuid=str(uuid4()),
            connection="default",
            queue="default",
            payload="website_handler",
            exception=str(e),
            failed_at=now(),
        )
        failed_job.save()
        print(f"Exception occurred: {e}")
        traceback.print_exc()
