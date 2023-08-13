from django import forms
from web.enums.chatbot_initial_prompt_enum import ChatBotInitialPromptEnum  # Import your enums module here
from web.utils.github_repo_url_validator import GithubRepoUrlValidator  # Import the custom validator here

class CreateChatbotViaCodebaseForm(forms.Form):
    repo = forms.URLField(validators=[GithubRepoUrlValidator()])

    name = forms.CharField(max_length=100, initial='My first chatbot')
    prompt_message = forms.CharField(max_length=255, required=False, initial=ChatBotInitialPromptEnum.AI_ASSISTANT_INITIAL_PROMPT.value)

    def get_repo_url(self):
        return self.cleaned_data['repo']

    def get_name(self):
        return self.cleaned_data['name']

    def get_prompt_message(self):
        return self.cleaned_data['prompt_message']

    class Meta:
        fields = ['repo', 'name', 'prompt_message']
