# api/views.py
from django.http import JsonResponse

def api_endpoint(request):
    # Your API logic goes here
    data = {'message': 'Hello, this is the API endpoint!'}
    return JsonResponse(data)
