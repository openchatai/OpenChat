from django.dispatch import Signal

# Define a custom Django signal
website_data_source_added = Signal(providing_args=[
    'chatbot_id', 'website_data_source_id',
])

# This function will be the Django signal receiver.
def add_website_data_source(sender, chatbot_id, website_data_source_id, **kwargs):
    # Add the WebsiteDataSource instance or perform any other necessary actions here.
    # For demonstration, we're just printing the details.
    print("Website data source was added:")
    print("Chatbot ID:", chatbot_id)
    print("WebsiteDataSource ID:", website_data_source_id)

# Connect the receiver function to the signal
website_data_source_added.connect(add_website_data_source)

# views.py (assuming you have a CreateWebsiteDataSourceView similar to CreateChatbotView)
# from django.http import HttpResponse
# from django.views import View
# from django.utils.decorators import method_decorator
# from django.views.decorators.csrf import csrf_exempt
# from django.http import JsonResponse
# from signals import website_data_source_added

# @method_decorator(csrf_exempt, name='dispatch')
# class CreateWebsiteDataSourceView(View):
#     def post(self, request, *args, **kwargs):
#         # Get the data source details from the request
#         chatbot_id = uuid.uuid4()  # Generate a UUID for the chatbot
#         website_data_source_id = uuid.uuid4()  # Generate a UUID for the website data source

#         # Emit the signal when a website data source is added
#         website_data_source_added.send(sender=WebsiteDataSource, chatbot_id=chatbot_id,
#                                        website_data_source_id=website_data_source_id)

#         return JsonResponse({'message': 'Website data source added successfully!'})
