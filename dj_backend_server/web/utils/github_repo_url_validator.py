import re
from django.core.exceptions import ValidationError
from django.core.validators import RegexValidator

class GithubRepoUrlValidator(RegexValidator):
    regex = r'^https?://github\.com/[\w-]+/[\w.-]+$'
    message = 'Enter a valid GitHub repository URL.'
