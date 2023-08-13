# views.py
from django.shortcuts import render, get_object_or_404, redirect
from web.models.chatbot import Chatbot
from web.services.handle_pdf_datasource import HandlePdfDataSource
from web.signals.pdf_datasource_was_added import pdf_data_source_added
from django.views.decorators.http import require_POST

@require_POST
def create(request, id):
    if request.FILES.getlist('pdffiles'):
        # Get the Chatbot object
        bot = get_object_or_404(Chatbot, id=id)

        # Process the uploaded files and create the data source
        handle_pdf_data_source = HandlePdfDataSource(bot, request.FILES.getlist('pdffiles'))
        data_source = handle_pdf_data_source.handle()

        # Trigger the event (Equivalent to Laravel's event(new PdfDataSourceWasAdded($bot->getId(), $dataSource->getId())))
        pdf_data_source_added.send(sender=None, bot_id=bot.id, data_source_id=data_source.id)

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
