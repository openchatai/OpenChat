from django.dispatch import Signal

# Define a custom Django signal
website_data_source_crawling_completed = Signal(providing_args=[
    'chatbot_id', 'website_data_source_id',
])

# This is just a placeholder for demonstration. You can replace it with your actual model for WebsiteDataSource.
class WebsiteDataSource:
    pass

# This function will be the Django signal receiver.
def website_crawling_completed(sender, chatbot_id, website_data_source_id, **kwargs):
    # Perform any necessary actions when website crawling is completed.
    # For demonstration, we're just printing the details.
    print("Website data source crawling was completed:")
    print("Chatbot ID:", chatbot_id)
    print("WebsiteDataSource ID:", website_data_source_id)

# Connect the receiver function to the signal
website_data_source_crawling_completed.connect(website_crawling_completed)

# # Emit the signal when website crawling is completed
# website_data_source_crawling_completed.send(sender=WebsiteDataSource, chatbot_id=chatbot_id,
#                                             website_data_source_id=website_data_source_id)
