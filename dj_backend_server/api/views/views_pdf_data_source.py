# views.py
from django.shortcuts import render, get_object_or_404, redirect
from django.core.exceptions import PermissionDenied
from api.models.chatbot import Chatbot
from api.services.handle_pdf_datasource import HandlePdfDataSource

def create_pdf_data_source(request, id):
    if request.method == 'POST':
        bot = get_object_or_404(Chatbot, id=id)
        files = request.FILES.getlist('pdffiles')
        data_source = HandlePdfDataSource(bot, files).handle()
        # Replace the 'event' functionality with any desired action in Django
        # (e.g., you can add a post_save signal to handle the event logic)
        
        return redirect('chatbot.settings-data', id=bot.id)
    else:
        raise PermissionDenied()

def show_pdf_data_sources(request, id):
    bot = get_object_or_404(Chatbot, id=id)
    pdf_data_sources = bot.pdfdatasource_set.all()
    return render(request, 'onboarding.other-data-sources-pdf', {'bot': bot, 'pdfDataSources': pdf_data_sources})
