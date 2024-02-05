import re
import random
import string
import uuid

def get_session_id(request, bot_id):
    cookie_name = 'chatbot_' + str(bot_id)

    session_id = request.COOKIES.get(cookie_name, None)
    if session_id is None:
        session_id = str(uuid.uuid4())
    return session_id


def generate_chatbot_name(repo_url, name=None):
    """
    Generate a chatbot name based on a Git repository URL and an optional name.

    Parameters:
        repo_url (str): The Git repository URL.
        name (str, optional): The name provided in the POST request (default is None).

    Returns:
        str: A generated chatbot name.

    If 'name' is not provided, a random suffix is added to a default name based on the last part of the Git URL.
    """
    # Extracting the last part of the Git URL
    last_part_of_git_url = re.search(r'[^/]+$', repo_url).group() if repo_url else ""

    # Creating a default name based on the last part of the Git URL
    default_name = f"chatbot-url-{last_part_of_git_url}"

    # If 'name' is not provided in the POST request, generate a random string
    if name is None:
        random_suffix = ''.join(random.choices(string.ascii_letters, k=5))
        name = f"{default_name}-{random_suffix}"

    return name