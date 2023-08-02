# views.py
import json
from django.http import JsonResponse, HttpResponseServerError
from django.views.decorators.csrf import csrf_exempt
from langchain.text_splitter import RecursiveCharacterTextSplitter
from api.utils import get_embeddings
from langchain.document_loaders.directory import DirectoryLoader
from api.utils import init_vector_store
from langchain.document_loaders import PyPDFLoader
import os
from api.interfaces import StoreOptions
@csrf_exempt
def pdf_handler(request):
    try:
        data = json.loads(request.body.decode('utf-8'))
        shared_folder = data.get('shared_folder')
        namespace = data.get('namespace')

        # https://blog.nextideatech.com/chat-with-documents-using-langchain-gpt-4-python/
        # try this too PyMuPDFLoader
        # directory_loader = DirectoryLoader("/Users/shanurrahman/Documents/Gitlab/opensource/OpenChat/dj_backend_server/website_data_sources/7e54d8ef993375547b87b9031292e0bbbd8b19b1", {
        #     '.pdf': lambda path: PyPDFLoader.load_and_split(path),
        # })
        path = "/Users/shanurrahman/Documents/Gitlab/opensource/OpenChat/dj_backend_server/website_data_sources/7e54d8ef993375547b87b9031292e0bbbd8b19b1"

        directory_loader = DirectoryLoader(path=path, glob="**/*.pdf", loader_cls=PyPDFLoader)

        raw_docs = directory_loader.load_and_split()

        text_splitter = RecursiveCharacterTextSplitter(chunk_size=1000, chunk_overlap=200)
        docs = text_splitter.split_documents(raw_docs)

        embeddings = get_embeddings()

        init_vector_store(docs, embeddings, StoreOptions(namespace))

        print('All is done, folder deleted')
        return JsonResponse({'message': 'Success'})

    except Exception as e:
        import traceback
        print(e)
        traceback.print_exc()
        return HttpResponseServerError({'error': str(e)})
