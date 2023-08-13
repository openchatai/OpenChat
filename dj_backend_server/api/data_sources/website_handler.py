import os
from django.http import JsonResponse

from langchain.document_loaders.directory import DirectoryLoader
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.document_loaders import TextLoader
from api.utils import init_vector_store
from api.utils.get_embeddings import get_embeddings
from api.interfaces import StoreOptions
# from  import delete_folder
from web.models.website_data_sources import WebsiteDataSource
from web.enums.website_data_source_status_enum import WebsiteDataSourceStatusType
def website_handler(shared_folder, namespace):
    website_data_source = WebsiteDataSource.objects.get(id=shared_folder)
    try:
        directory_path = os.path.join("website_data_sources", shared_folder)
        directory_loader = DirectoryLoader(directory_path, glob="**/*.txt", loader_cls=TextLoader, use_multithreading=True)

        raw_docs = directory_loader.load()

        text_splitter = RecursiveCharacterTextSplitter(chunk_size=1000, chunk_overlap=200, length_function=len)

        docs = text_splitter.split_documents(raw_docs)

        print("docs -->", docs);
        embeddings = get_embeddings()

        init_vector_store(docs, embeddings, StoreOptions(namespace=namespace))

        website_data_source.crawling_status = WebsiteDataSourceStatusType.COMPLETED.value
        website_data_source.save()
        # delete_folder(folder_path=directory_path)
        print('All is done, folder deleted...')
    except Exception as e:
        website_data_source.crawling_status = WebsiteDataSourceStatusType.FAILED.value
        website_data_source.save()
        import traceback
        print(e)
        traceback.print_exc()
