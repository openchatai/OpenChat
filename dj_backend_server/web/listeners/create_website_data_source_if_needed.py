# listeners.py

from django.dispatch import receiver
from signals.chatbot_was_created import chatbot_was_created
from signals.website_data_source_was_added import website_data_source_added
from models.website_data_sources import WebsiteDataSource

@receiver(chatbot_was_created)
def create_website_data_source(sender, **kwargs):
    event = kwargs['event']

    if not isinstance(event, chatbot_was_created):
        return

    if not event.get_chatbot_website():
        return

    bot_id = event.get_chatbot_id()
    website_url = event.get_chatbot_website()

    # Create a new WebsiteDataSource instance and save it to the database
    data_source = WebsiteDataSource.objects.create(
        chatbot_id=bot_id,
        root_url=website_url,
        icon=event.get_logo(website_url)  # Assuming you have implemented the get_logo method
    )

    # Trigger the WebsiteDataSourceWasAdded signal with relevant data
    website_data_source_added.send(sender=None, bot_id=bot_id, data_source_id=data_source.id)
