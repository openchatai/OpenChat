from django.dispatch import Signal

# Define a custom Django signal
codebase_data_source_added = Signal(providing_args=[
    'chatbot_id', 'codebase_data_source_id',
])

# This function will be the Django signal receiver.
def add_codebase_data_source(sender, chatbot_id, codebase_data_source_id, **kwargs):
    # Add the CodebaseDataSource instance or perform any other necessary actions here.
    # For demonstration, we're just printing the details.
    print("Codebase data source was added:")
    print("Chatbot ID:", chatbot_id)
    print("CodebaseDataSource ID:", codebase_data_source_id)

# Connect the receiver function to the signal
codebase_data_source_added.connect(add_codebase_data_source)

# # Emit the signal when a codebase data source is added
# codebase_data_source_added.send(sender=CodebaseDataSource, chatbot_id=chatbot_id,
#                                 codebase_data_source_id=codebase_data_source_id)
