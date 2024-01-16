import os
from django.http import JsonResponse
import traceback
from langchain.document_loaders.directory import DirectoryLoader
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.document_loaders import TextLoader
from api.utils import init_vector_store
from api.utils.get_embeddings import get_embeddings
from api.interfaces import StoreOptions
# from  import delete_folder
from web.models.website_data_sources import WebsiteDataSource
from web.enums.website_data_source_status_enum import WebsiteDataSourceStatusType
from web.models.failed_jobs import FailedJob
from datetime import datetime
from uuid import uuid4

def website_handler(shared_folder, namespace):
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
    website_data_source = WebsiteDataSource.objects.get(id=shared_folder)
    try:
        directory_path = os.path.join("website_data_sources", shared_folder)
        directory_loader = DirectoryLoader(directory_path, glob="**/*.txt", loader_cls=TextLoader, use_multithreading=True)

        raw_docs = directory_loader.load()

        text_splitter = RecursiveCharacterTextSplitter(chunk_size=4000, chunk_overlap=200, length_function=len)

        docs = text_splitter.split_documents(raw_docs)

        print("website docs -->", docs);
        embeddings = get_embeddings()

        init_vector_store(docs, embeddings, StoreOptions(namespace=namespace))

        website_data_source.crawling_status = WebsiteDataSourceStatusType.COMPLETED.value
        website_data_source.save()
        print('Website embeddings, done ...')
    except Exception as e:
        website_data_source.crawling_status = WebsiteDataSourceStatusType.FAILED.value
        website_data_source.save()
        failed_job = FailedJob(uuid=str(uuid4()), connection='default', queue='default', payload='website_handler', exception=str(e), failed_at=datetime.now())
        failed_job.save()
        print(f"Exception occurred: {e}")
        traceback.print_exc()
