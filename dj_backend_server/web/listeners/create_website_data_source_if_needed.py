# listeners.py

from django.dispatch import receiver
from signals.chatbot_was_created import chatbot_was_created
from signals.website_data_source_was_added import website_data_source_added
from models.website_data_sources import WebsiteDataSource
from web.utils.get_logo_from_url import get_logo_from_url
from uuid import uuid4

@receiver(chatbot_was_created)
def create_website_data_source(sender, **kwargs):
    sender, id, name, website, prompt_message = {**kwargs}

    print("called the receiver for chatbot was created event")

    # if not isinstance(event, chatbot_was_created):
    #     return

    if not website:
        return

    # Create a new WebsiteDataSource instance and save it to the database
    data_source = WebsiteDataSource.objects.create(
        id=uuid4(),
        chatbot_id=id,
        root_url=website,
        icon=get_logo_from_url(website)
    )

    # Trigger the WebsiteDataSourceWasAdded signal with relevant data
    website_data_source_added.send(sender=create_website_data_source.__name__, bot_id=id, data_source_id=data_source.id)
