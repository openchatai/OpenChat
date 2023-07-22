# onboarding/views.py
from django.shortcuts import render

def welcome(request):
    return render(request, 'onboarding/step-0.html')

def data_sources(request):
    return render(request, 'onboarding/step-1.html')

def data_sources_website(request):
    return render(request, 'onboarding/step-2.html')

def data_sources_pdf(request):
    return render(request, 'onboarding/step-2-pdf.html')

def config(request):
    return render(request, 'onboarding/step-3.html')

def done(request):
    return render(request, 'onboarding/step-4.html')

def data_sources_codebase(request):
    return render(request, 'onboarding/step-2-codebase.html')
