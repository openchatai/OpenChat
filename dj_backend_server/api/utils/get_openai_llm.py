from langchain.llms import AzureOpenAI
import os
from dotenv import load_dotenv

load_dotenv()

def get_openai_model():
    """Get an Azure OpenAI Language Model instance.
    
    Returns:
        llm (AzureOpenAI): An instance of the AzureOpenAI client, configured
            with the specified OpenAI deployment, model, and parameters.
    """
    # Get OpenAI credentials from environment variables
    openai_api_key = os.environ['OPENAI_API_KEY']
    openai_deployment_name = os.environ['OPENAI_DEPLOYMENT_NAME']
    openai_model_name = os.environ['OPENAI_COMPLETION_MODEL']

    # Configure the AzureOpenAI client
    llm = AzureOpenAI(
        openai_api_key=openai_api_key,
        deployment_name=openai_deployment_name, 
        model_name=openai_model_name,
        max_tokens=1000,
        n=1,
        temperature=0,
    )

    return llm
