# views.py
from django.shortcuts import render, get_object_or_404, redirect
from web.models.chatbot import Chatbot
from web.services.handle_pdf_datasource import HandlePdfDataSource
from web.signals.pdf_datasource_was_added import pdf_data_source_added
from django.views.decorators.http import require_POST
import hashlib

@require_POST
def create(request, id):
    delete_folder_flag = 'delete_folder_flag' in request.POST
    ocr_pdf_file = 'ocr_pdf_file' in request.POST
    if request.FILES.getlist('pdffiles'):
        # Get the Chatbot object
        bot = get_object_or_404(Chatbot, id=id)

        # Iterate over the uploaded files
        for upload_file in request.FILES.getlist('pdffiles'):
            # Calculate the hash of the uploaded file
            upload_file_hash = hashlib.md5(upload_file.read()).hexdigest()

            # Check if a file with the same hash and filename already exists in the files_info field of the PdfDataSource model related to the current chatbot
            for pdf_data_source in bot.pdf_data_sources.all():
                for file_info in pdf_data_source.get_files_info():
                    if file_info['hash'] == upload_file_hash and file_info['original_name'] == upload_file.name:
                        print(f"File with hash {upload_file_hash} and/or name {upload_file.name} already exists")
                        break
                else:
                    continue
                break
            else:
                # If no such file exists, proceed with the upload as usual
                handle_pdf_data_source = HandlePdfDataSource(bot, [upload_file])
                data_source = handle_pdf_data_source.handle()
                pdf_data_source_added.send(sender=None, bot_id=bot.id, data_source_id=data_source.id, delete_folder_flag=delete_folder_flag, ocr_pdf_file=ocr_pdf_file)

        # Redirect to the chatbot settings page with a success message
        return redirect('chatbot.settings-data', id=bot.id)

    # Handle the case when no files are uploaded or it's a GET request
    return redirect('chatbot.settings-data', id=id)

def show(request, id):
    # Get the Chatbot object or raise a 404 error if not found
    bot = get_object_or_404(Chatbot, id=id)

    # Retrieve the PDF data sources associated with the chatbot
    pdf_data_sources_list = bot.get_pdf_files_data_sources()

    # Assuming you have an HTML template named 'onboarding_other_data_sources_pdf.html'
    return render(request, 'onboarding/other-data-sources-pdf.html', {'bot': bot, 'pdfDataSources': pdf_data_sources_list})
