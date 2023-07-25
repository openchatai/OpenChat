from django.dispatch import Signal

# Define a custom Django signal
chatbot_created = Signal(providing_args=[
    'chatbot_id', 'chatbot_name', 'chatbot_website', 'chatbot_prompt_message'
])

# This function will be the Django signal receiver.
def create_chatbot(sender, chatbot_id, chatbot_name, chatbot_website, chatbot_prompt_message, **kwargs):
    # Create a Chatbot instance or perform any other necessary actions here.
    # For demonstration, we're just printing the details.
    print("Chatbot was created:")
    print("ID:", chatbot_id)
    print("Name:", chatbot_name)
    print("Website:", chatbot_website)
    print("Prompt Message:", chatbot_prompt_message)

# Connect the receiver function to the signal
chatbot_created.connect(create_chatbot)

# Emit the signal when the chatbot is created
# chatbot_created.send(sender=Chatbot, chatbot_id=chatbot_id, chatbot_name=chatbot_name,
#                         chatbot_website=chatbot_website, chatbot_prompt_message=chatbot_prompt_message)