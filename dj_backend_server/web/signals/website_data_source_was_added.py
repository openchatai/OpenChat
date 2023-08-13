from django.dispatch import Signal

# Define a custom Django signal
website_data_source_added = Signal()

# This function will be the Django signal receiver.
def add_website_data_source(sender, chatbot_id, website_data_source_id, **kwargs):
    # Add the WebsiteDataSource instance or perform any other necessary actions here.
    # For demonstration, we're just printing the details.
    print("Website data source was added:")
    print("Chatbot ID:", chatbot_id)
    print("WebsiteDataSource ID:", website_data_source_id)

# Connect the receiver function to the signal
# website_data_source_added.connect(add_website_data_source)