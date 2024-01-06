# views.py
import json
import io
from django.views.decorators.csrf import csrf_exempt
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.document_loaders.directory import DirectoryLoader
from langchain.document_loaders import PyPDFium2Loader
from langchain.document_loaders import TextLoader
from api.utils import get_embeddings
from api.utils import init_vector_store
import os
from web.utils.delete_foler import delete_folder
from api.interfaces import StoreOptions
import requests
import traceback
from web.models.failed_jobs import FailedJob
from django.utils import timezone
from uuid import uuid4
from web.models.pdf_data_sources import PdfDataSource
from django.shortcuts import get_object_or_404
from api.utils.make_chain import process_text_with_llm

@csrf_exempt
def pdf_handler(shared_folder: str, namespace: str, delete_folder_flag: bool):
    
    # Convert delete_folder_flag to boolean (send 0 - FALSE or 1 - TRUE)
    delete_folder_flag = bool(int(delete_folder_flag))
    
    try:
        #TODO: When will be multiple external library to choose, need to change.
        if os.environ.get("PDF_LIBRARY") == "external":
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
        # pdf_data_source.ingest_status = 'failed'
        # pdf_data_source.save()
        failed_job = FailedJob(uuid=str(uuid4()), connection='default', queue='default', payload='pdf_handler', exception=str(e),failed_at=timezone.now())
        failed_job.save()
        print("Exception occurred:", e)
        traceback.print_exc()

@csrf_exempt
def process_pdf(FilePath,directory_path):
    #pdf_data_source = PdfDataSource.objects.get(folder_name=FilePath)
    #pdf_data_source = PdfDataSource.objects.get(folder_name=directory_path)
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
        #   pdf_data_source.ingest_status = 'failed'
        #   pdf_data_source.save()
          failed_job = FailedJob(uuid=str(uuid4()), connection='default', queue='default', payload=FilePath, exception='File not found', failed_at=timezone.now())
          failed_job.save()
          print(f"File not found: {FilePath}")
          return
    
    try:
        r = requests.post(RequestUrl, data=image_data, auth=(UserName, LicenseCode))
        
        # Decode Output response
        jobj = json.loads(r.content)
        
        ocrError = str(jobj["ErrorMessage"])

        if ocrError != '':
                #Error occurs during recognition
                raise Exception("Recognition Error: " + ocrError)

        # Extracted text from first or single page
        # print(str(jobj["OCRText"]))

        # Extracted text from first or single page
        ocrText = str(jobj["OCRText"])

        # Extract the filename without the extension
        file_path = os.path.splitext(os.path.basename(FilePath))[0]

        # Create a new TXT file with the same name in the same directory
        txt_file_path = os.path.join(directory_path, file_path + '.txt')
        if os.environ.get("OCR_LLM", "0") == "1":
            # Write the OCR text into an in-memory text stream
            txt_file = io.StringIO(ocrText)
            mode = 'assistant'
            initial_prompt = f'Objective: To enhance official documents written. ' \
            f'\nInput Data: The text of a document which may contain grammatical errors, typos, formatting issues, and stylistic inconsistencies from OCR result. ' \
            f'\nFunctional Requirements: Detection and Correction of Grammatical and Typographical Errors: Identify and correct spelling and punctuation errors. Check grammatical agreements within sentences.' \
            f'\nStandardization of Style: Adjust the text to ensure coherence and stylistic uniformity in accordance with official writing standards.' \
            f'\nClarification of Text Structure: Restructure sentences to improve clarity and readability, without altering the original meaning. Keep and answer the detected language from the document.' \
            f'\nDocument Formatting: Implement a formatting system that adjusts the alignment of text, lists, and other structural elements for a professional presentation.' \
            f'\nOutput Data: This is the corrected and enhanced document. Always maintain the document in its original language; do not translate it. Respond only in the language detected from the document. Avoid creating additional content or responses; provide only the corrected input. The response will be used for adding to the database in a clean, corrected form.' \
            f'\nThe text: {{text}}. ' 
            
            # print (f"Debug: initial_prompt: {initial_prompt}")
            
            # Call LLM and write the result into a new text file
            process_text_with_llm(txt_file, mode, initial_prompt)
            final_text = txt_file.getvalue()
            with open(txt_file_path, 'w') as f:
                f.write(final_text)
        else:
            # Write the OCR text into the new TXT file
            with open(txt_file_path, 'w') as txt_file:
                txt_file.write(ocrText)

    except Exception as e:
        if str(e) == "Recognition Error: Maximum page limit exceeded":
            print("The document exceeds the maximum page limit for the OCR service.")
        else:
            print(f"Exception occurred: {e}")
        # pdf_data_source.ingest_status = 'failed'
        # pdf_data_source.save()
        failed_job = FailedJob(uuid=str(uuid4()), connection='default', queue='default', payload=FilePath, exception=str(e), failed_at=timezone.now())
        failed_job.save()
        traceback.print_exc()

@csrf_exempt
def txt_to_vectordb(shared_folder: str, namespace: str, delete_folder_flag: bool):
    try:
        #pdf_data_source = PdfDataSource.objects.get(folder_name=shared_folder)
        directory_path = os.path.join("website_data_sources", shared_folder)

        #TODO: When will be multiple external library to choose, need to change.    
        if os.environ.get("PDF_LIBRARY") == "external":
            directory_loader = DirectoryLoader(directory_path, glob="**/*.txt", loader_cls=TextLoader, use_multithreading=True)
        else:
            directory_loader = DirectoryLoader(directory_path, glob="**/*.pdf", loader_cls=PyPDFium2Loader, use_multithreading=True)

        raw_docs = directory_loader.load()

        text_splitter = RecursiveCharacterTextSplitter(chunk_size=4000, chunk_overlap=200, length_function=len)

        docs = text_splitter.split_documents(raw_docs)

        print("docs -->", docs);
        if not docs:
             print("No documents were processed successfully.")
             return

        embeddings = get_embeddings()

        init_vector_store(docs, embeddings, StoreOptions(namespace=namespace))

        print(f'Folder need or not to delete. {delete_folder_flag}')
        # Delete folder if flag is set
        if delete_folder_flag:
            delete_folder(folder_path=directory_path)
            print(f'All is done, folder deleted {delete_folder_flag}')

    except Exception as e:
        # pdf_data_source.ingest_status = 'failed'
        # pdf_data_source.save()
        failed_job = FailedJob(uuid=str(uuid4()), connection='default', queue='default', payload='txt_to_vectordb', exception=str(e),failed_at=timezone.now())
        failed_job.save()
        print(e)
        traceback.print_exc()
