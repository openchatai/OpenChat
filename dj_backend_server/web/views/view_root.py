# views.py in your app
from django.shortcuts import render

def root_navigation_view(request):
    return render(request, 'root_navigation.html')  # Replace 'root_navigation.html' with your desired template name