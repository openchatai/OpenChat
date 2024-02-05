from django.db import models
from web.models.chatbot import Chatbot

class ChatHistory(models.Model):
    """
    This Django model represents a chat history record. Each record includes an ID, a chatbot ID, a session ID, a flag indicating
    whether the message is from the user or the bot, the message text, and timestamps for when the record was created and last updated.

    Attributes:
        id (CharField): The ID of the chat history record. It is a string of up to 36 characters.
        chatbot_id (CharField): The ID of the chatbot involved in the chat. It is a string of up to 36 characters.
        session_id (CharField): The ID of the chat session. It is a string of up to 255 characters.
        from_user (CharField): A flag indicating whether the message is from the user or the bot. It is stored in the 'from' column
        in the database.
        message (TextField): The message text.
        created_at (DateTimeField): The timestamp for when the record was created. It is automatically set when the record is created.
        updated_at (DateTimeField): The timestamp for when the record was last updated. It is automatically set when the record is updated.
    """
    id = models.CharField(max_length=36, primary_key=True)
    chatbot_id = models.CharField(max_length=36, null=True)
    session_id = models.CharField(max_length=255, null=True)
    from_user = models.CharField(max_length=255, db_column="from")
    message = models.TextField()
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    feedback = models.BooleanField(null=True, blank=True, help_text="Feedback indicating if the response was helpful")


    def __str__(self):
        """
        Returns a string representation of the chat history record. The string representation is the message text.

        Returns:
            str: The message text.
        """
        return self.message


    def set_id(self, _id):
        """
        Sets the ID of the chat history record.

        Args:
            _id (str): The new ID.
        """
        self.id = _id
        
        
    def is_from_user(self):
        """
        Checks if the message is from the user.

        Returns:
            bool: True if the message is from the user, False otherwise.
        """
        return self.from_user


    def is_from_bot(self):
        """
        Checks if the message is from the bot.

        Returns:
            bool: True if the message is from the bot, False otherwise.
        """
        return not self.from_user


    def set_from_user(self):
        """
        Sets the message as being from the user.
        """
        self.from_user = True


    def set_from_bot(self):
        """
        Sets the message as being from the bot.
        """
        self.from_user = False


    def set_message(self, message):
        """
        Sets the message text.

        Args:
            message (str): The new message text.
        """
        self.message = message


    def get_message(self):
        """
        Retrieves the message text.

        Returns:
            str: The message text.
        """
        return self.message


    def get_created_at(self):
        """
        Retrieves the timestamp for when the record was created.

        Returns:
            datetime: The timestamp for when the record was created.
        """
        return self.created_at


    def set_chatbot_id(self, chatbot_id):
        """
        Sets the ID of the chatbot involved in the chat.

        Args:
            chatbot_id (str): The new chatbot ID.
        """
        self.chatbot_id = chatbot_id


    def set_session_id(self, session_id):
        """
        Sets the ID of the chat session.

        Args:
            session_id (str): The new session ID.
        """
        self.session_id = session_id


    class Meta:
        db_table = 'chat_histories'  # Replace 'chat_history' with the actual table name in the database