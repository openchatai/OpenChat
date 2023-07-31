# views.py
import json
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.embeddings.openai import OpenAIEmbeddings
from langchain.document_loaders.directory import DirectoryLoader
from api.utils import init_vector_store
from langchain.document_loaders import PyPDFium2Loader

@csrf_exempt
def pdf_handler(request):
    try:
        data = json.loads(request.body.decode('utf-8'))
        shared_folder = data.get('shared_folder')
        namespace = data.get('namespace')

        directory_loader = DirectoryLoader(f"/tmp/website_data_sources/{shared_folder}", {
            '.pdf': lambda path: PyPDFium2Loader.load_and_split(path),
        })

        raw_docs = directory_loader.load()

        text_splitter = RecursiveCharacterTextSplitter(chunkSize=1000, chunkOverlap=200)
        docs = text_splitter.split_documents(raw_docs)

        embeddings = OpenAIEmbeddings()

        init_vector_store(docs, embeddings, namespace=namespace)

        print('All is done, folder deleted')
        return JsonResponse({'message': 'Success'})

    except Exception as e:
        print(e)
        return JsonResponse({'error': str(e)})
