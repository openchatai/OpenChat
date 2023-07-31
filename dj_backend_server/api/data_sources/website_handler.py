import os
from django.http import JsonResponse

from langchain.document_loaders.directory import DirectoryLoader
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.document_loaders import TextLoader
from langchain.embeddings.openai import OpenAIEmbeddings
from api.utils import init_vector_store
import json

def website_handler(request):
    try:
        data = json.loads(request.body)
        shared_folder = data.get('shared_folder')
        namespace = data.get('namespace')


        directory_path = os.path.join("/tmp/website_data_sources/", shared_folder)
        directory_loader = DirectoryLoader(directory_path, {'.txt': lambda path: TextLoader(path)})

        raw_docs = directory_loader.load()

        text_splitter = RecursiveCharacterTextSplitter(chunkSize=1000, chunkOverlap=200)

        docs = text_splitter.split_documents(raw_docs)

        embeddings = OpenAIEmbeddings()

        init_vector_store(docs, embeddings, namespace=namespace)
        print('All is done, folder deleted')
        return JsonResponse({'message': 'Success'}, status=200)
    except Exception as e:
        print(e)
        return JsonResponse({'error': str(e), 'line': e.lineno}, status=500)
