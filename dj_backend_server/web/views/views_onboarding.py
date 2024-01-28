# onboarding/views.py
from django.shortcuts import render, redirect
from django.shortcuts import get_object_or_404
from django.contrib import messages
from web.models.chatbot import Chatbot
import re

def is_valid_website_url(url):
    # Basic URL validation using a regular expression
    url_pattern = re.compile(
        r'^(https?://)?(www\.)?[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(\/\S*)?$'
    )
    return bool(url_pattern.match(url))

# ... rest of the file remains unchanged ...
def welcome(request):
    return render(request, 'onboarding/step-0.html')

def data_sources(request):
    if request.method == 'POST':
        # Perform any necessary processing for the form data

        # For example, if you want to validate a website URL
        website_url = request.POST.get('website')
        if not is_valid_website_url(website_url):
            messages.error(request, "Please enter a valid website URL, it's important that your website is live and accessible")

            # Redirect back to the same page to display the error message
            return redirect('data_sources')

        # Process the data and do the necessary actions

        # Show a success message
        messages.success(request, "Website data source added successfully!")

        # Redirect to the next page or the same page if needed
        return redirect('onboarding/step-2.html')

    return render(request, 'onboarding/step-1.html')

def data_sources_website(request):
    return render(request, 'onboarding/step-2.html')

def data_sources_codebase(request):
    return render(request, 'onboarding/step-2-codebase.html')

def data_sources_pdf(request):
    submit_text = """all.step_6_please_wait"""
    context = {
        'submit_text': submit_text
    }

    return render(request, 'onboarding/step-2-pdf.html', context)

def config(request, id):
    return render(request, 'onboarding/step-3.html')

def done(request, id):
    chatbot = get_object_or_404(Chatbot, id=id)
    context = {
        "id": id,
        "chatbot_name": chatbot.name
    }
    return render(request, 'onboarding/step-4.html', context)

def skip_pdf_upload(request):
    return redirect('onboarding.pdf.create')
