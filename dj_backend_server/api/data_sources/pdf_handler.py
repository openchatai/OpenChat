# views.py
import json
from django.http import JsonResponse, HttpResponseServerError
from django.views.decorators.csrf import csrf_exempt
from langchain.text_splitter import RecursiveCharacterTextSplitter
from api.utils import get_embeddings
from langchain.document_loaders.directory import DirectoryLoader
from api.utils import init_vector_store
from langchain.document_loaders import PyPDFium2Loader
from pathlib import Path
import os

from api.interfaces import StoreOptions
@csrf_exempt
def pdf_handler(request):
    try:
        data = json.loads(request.body.decode('utf-8'))
        shared_folder = data.get('shared_folder')
        namespace = data.get('namespace')

        directory_path = os.path.join("website_data_sources", shared_folder)

        directory_loader = DirectoryLoader(path=directory_path, glob="**/*.pdf", loader_cls=PyPDFium2Loader)

        raw_docs = directory_loader.load_and_split()

        text_splitter = RecursiveCharacterTextSplitter(separators=["\n"], chunk_size=1000, chunk_overlap=200,length_function=len)
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
