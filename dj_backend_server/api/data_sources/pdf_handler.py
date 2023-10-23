# views.py
import json
from django.views.decorators.csrf import csrf_exempt
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.document_loaders.directory import DirectoryLoader
from langchain.document_loaders import TextLoader
from api.utils import get_embeddings
from api.utils import init_vector_store
import os
from web.utils.delete_foler import delete_folder
from api.interfaces import StoreOptions
import requests
import traceback

@csrf_exempt
def pdf_handler(shared_folder: str, namespace: str, delete_folder_flag: bool):
    try:
        directory_path = os.path.join("website_data_sources", shared_folder)
        # print(f"Debug: Processing folder {directory_path}")

        if os.path.exists(directory_path):
            print(f"Debug: Directory exists. Files: {os.listdir(directory_path)}")
        else:
            print(f"Debug: Directory does not exist")

        for filename in os.listdir(directory_path):
                if filename.endswith(".pdf"):
                    file_path = os.path.join(directory_path, filename)
                    process_pdf(file_path,directory_path)

        txt_to_vectordb(shared_folder, namespace, delete_folder_flag)

    except Exception as e:
        print("Exception occurred:", e)
        traceback.print_exc()

@csrf_exempt
def process_pdf(FilePath,directory_path):

    UserName = os.environ.get("OCR_USERNAME")
    LicenseCode = os.environ.get("OCR_LICCODE")
    gettext = True
    outputformat = "txt"
    language = os.environ.get("OCR_LANGUAGE", "english")
    pagerange="allpages"
    resturl="http://www.ocrwebservice.com/restservices/processDocument"

    RequestUrl = f'{resturl}?pagerange={pagerange}&language={language}&outputformat={outputformat}&gettext={gettext}';
    #print(f"Debug: RequestUrl: {RequestUrl}")
    #print (f"FilePath: {FilePath}")
    
    try:
        with open(FilePath, 'rb') as image_file:
            image_data = image_file.read()
    except FileNotFoundError:
         print(f"File not found: {FilePath}")
         return

    r = requests.post(RequestUrl, data=image_data, auth=(UserName, LicenseCode))
    
    # Decode Output response
    jobj = json.loads(r.content)
    
    ocrError = str(jobj["ErrorMessage"])

    if ocrError != '':
            #Error occurs during recognition
            print ("Recognition Error: " + ocrError)
            exit()

    # Extracted text from first or single page
    # print(str(jobj["OCRText"]))

    # Extracted text from first or single page
    ocrText = str(jobj["OCRText"])

    # Extract the filename without the extension
    base_filename = os.path.splitext(os.path.basename(FilePath))[0]

    # Create a new TXT file with the same name in the same directory
    txt_file_path = os.path.join(directory_path, base_filename + '.txt')

    # Write the OCR text into the new TXT file
    with open(txt_file_path, 'w') as txt_file:
        txt_file.write(ocrText)

@csrf_exempt
def txt_to_vectordb(shared_folder: str, namespace: str, delete_folder_flag: bool):
    try:
            directory_path = os.path.join("website_data_sources", shared_folder)
            directory_loader = DirectoryLoader(directory_path, glob="**/*.txt", loader_cls=TextLoader, use_multithreading=True)

            raw_docs = directory_loader.load()

            text_splitter = RecursiveCharacterTextSplitter(chunk_size=1000, chunk_overlap=200, length_function=len)

            docs = text_splitter.split_documents(raw_docs)

            print("docs -->", docs);
            embeddings = get_embeddings()

            init_vector_store(docs, embeddings, StoreOptions(namespace=namespace))

            # Delete folder if flag is set
            if delete_folder_flag:
                delete_folder(folder_path=directory_path)
                print('All is done, folder deleted')

    except Exception as e:
        import traceback
        print(e)
        traceback.print_exc()
