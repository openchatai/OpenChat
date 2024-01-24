from enum import Enum
import random

class ChatBotDefaults(Enum):
    @staticmethod
    def NAME():
        random_number = random.randint(1000, 9999)
        return f"ChatBot {random_number}"
