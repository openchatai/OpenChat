from langchain.llms import AzureOpenAI, OpenAI
import os
from dotenv import load_dotenv

load_dotenv()

# Azure OpenAI Language Model client
def get_azure_openai_llm():
    """Returns AzureOpenAI instance configured from environment variables"""
    
    openai_api_type = os.environ['OPENAI_API_TYPE']
    openai_api_key = os.environ['AZURE_OPENAI_API_KEY']
    openai_deployment_name = os.environ['AZURE_OPENAI_DEPLOYMENT_NAME']
    openai_model_name = os.environ['AZURE_OPENAI_COMPLETION_MODEL']
    openai_api_version = os.environ['AZURE_OPENAI_API_VERSION']
    openai_api_base=os.environ['AZURE_OPENAI_API_BASE']
    return AzureOpenAI(
        openai_api_base=openai_api_base,
        openai_api_key=openai_api_key,
        deployment_name=openai_deployment_name,
        model_name=openai_model_name,
        openai_api_type=openai_api_type,
        openai_api_version=openai_api_version,
        temperature=0,
        batch_size=8
    )

# OpenAI Language Model client  
def get_openai_llm():
    """Returns OpenAI instance configured from environment variables"""
    
    openai_api_key = os.environ['OPENAI_API_KEY']
    
    return OpenAI(
        temperature=0,
        openai_api_key=openai_api_key
    )
        
# recommend not caching initially, and optimizing only if you observe a clear performance benefit from caching the clients. 
# The simplest thing that works is often best to start.
def get_llm():
    """Returns LLM client instance based on OPENAI_API_TYPE"""
    
    clients = {
        'azure': get_azure_openai_llm,
        'openai': get_openai_llm
    }
    
    api_type = os.environ.get('OPENAI_API_TYPE')
    if api_type not in clients:
        raise ValueError(f"Invalid OPENAI_API_TYPE: {api_type}")
    
    return clients[api_type]()