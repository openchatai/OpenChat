# views.py
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from langchain.text_splitter import RecursiveCharacterTextSplitter
from api.utils import get_embeddings
from langchain.document_loaders import GitLoader
from api.utils import init_vector_store
from api.interfaces import StoreOptions

# https://python.langchain.com/docs/integrations/document_loaders/git
@csrf_exempt
def codebase_handler(repo_path: str, namespace: str):
    try:
        folder_path = f"website_data_sources/{namespace}"
        loader = GitLoader(repo_path=folder_path, clone_url=repo_path, branch="master")

        raw_docs = loader.load()

        print('Loaded documents')

        text_splitter = RecursiveCharacterTextSplitter(separators=["\n"], chunk_size=1000, chunk_overlap=200,length_function=len)
        docs = text_splitter.split_documents(raw_docs)

        embeddings = get_embeddings()

        init_vector_store(docs, embeddings, options=StoreOptions(namespace))

        print('Indexed documents. all done!')
    except Exception as e:
        print(e)