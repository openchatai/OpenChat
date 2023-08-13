# listeners.py

from web.signals.website_data_source_was_added import website_data_source_added
from api.tasks import start_recursive_crawler_task

@website_data_source_added.connect
def handle_website_data_source_add(sender, **kwargs):
    # Get the WebsiteDataSource object
    data_source_id = kwargs['data_source_id']
    chatbot_id = kwargs['bot_id']
    start_recursive_crawler_task.delay(sender, data_source_id, chatbot_id) # The function below will now be executed by celery