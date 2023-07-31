# views.py
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.embeddings.openai import OpenAIEmbeddings
from langchain.document_loaders import GitLoader
from api.utils import init_vector_store
import json

# https://python.langchain.com/docs/integrations/document_loaders/git
@csrf_exempt
def codebase_handler(request):
    try:
        data = json.loads(request.body.decode('utf-8'))
        repo_path = data.get('repo')
        namespace = data.get('namespace')
        loader = GitLoader(repo_path=repo_path, branch="main", recursive=True, unknown="warn")

        raw_docs = loader.load()

        print('Loaded documents')

        text_splitter = RecursiveCharacterTextSplitter(chunkSize=1000, chunkOverlap=200)
        docs = text_splitter.split_documents(raw_docs)

        print('Split documents')

        embeddings = OpenAIEmbeddings()

        init_vector_store(docs, embeddings, namespace=namespace)

        print('Indexed documents. all done!')
        return JsonResponse({'message': 'Success'})

    except Exception as e:
        print(e)
        return JsonResponse({'error': str(e)})