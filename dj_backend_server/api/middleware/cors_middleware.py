from django.utils.deprecation import MiddlewareMixin
from web.models.chatbot import Chatbot
import os

class CorsMiddleware(MiddlewareMixin):
    def process_response(self, request, response):
        # Get the origin of the request
        origin = request.META.get('HTTP_ORIGIN')

        # Check if the origin is in the database
        # Get APP_URL from environment variables
        app_url = os.getenv('APP_URL')
        #print(f"Origin of the APP_URL: {app_url} == {origin}")

        # Check if the origin is in the database or equal to APP_URL
        origin_in_db = origin == app_url or Chatbot.objects.filter(website=origin).exists()
        
        if origin_in_db:    
            # Add the 'Access-Control-Allow-Origin' header to the response
            response['Access-Control-Allow-Origin'] = origin
            response['Access-Control-Allow-Methods'] = 'GET, POST, OPTIONS'
            response['Access-Control-Allow-Headers'] = 'X-Requested-With, Content-Type, X-Bot-Token'

        #print(f"Website URLs checked: {[chatbot.website for chatbot in Chatbot.objects.all()]}")
        # print(f"Response status code: {response.status_code}")
        # print(f"Response content: {response.content}")
        #print(f"Response headers: {response.headers}")
        return response