from django.utils.deprecation import MiddlewareMixin
from web.models.chatbot import Chatbot

class CorsMiddleware(MiddlewareMixin):
    def process_response(self, request, response):
        # Get the origin of the request
        origin = request.META.get('HTTP_ORIGIN')

        # Check if the origin is in the database
        origin_in_db = Chatbot.objects.filter(website=origin).exists()
        print(f"Origin of the request: {origin}")
        print(f"Is the origin in the database: {origin_in_db}")

        if origin_in_db:
            # Add the 'Access-Control-Allow-Origin' header to the response
            response['Access-Control-Allow-Origin'] = origin
            response['Access-Control-Allow-Methods'] = 'GET, POST, OPTIONS'
            response['Access-Control-Allow-Headers'] = 'X-Requested-With, Content-Type, X-Bot-Token'

        print(f"Website URLs checked: {[chatbot.website for chatbot in Chatbot.objects.all()]}")
        print(f"Response status code: {response.status_code}")
        print(f"Response content: {response.content}")
        print(f"Response headers: {response.headers}")


        return response