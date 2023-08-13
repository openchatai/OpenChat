from django import forms
from web.models.chatbot import Chatbot
from web.enums.chatbot_initial_prompt_enum import ChatBotInitialPromptEnum

class ChatbotForm(forms.ModelForm):
    name = forms.CharField(max_length=255, initial='My first chatbot')
    prompt_message = forms.CharField(max_length=255, required=False, initial= ChatBotInitialPromptEnum.AI_ASSISTANT_INITIAL_PROMPT.value)

    class Meta:
        model = Chatbot
        fields = ['name', 'website', 'status', 'prompt_message', 'token']
