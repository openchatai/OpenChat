from langchain.llms import AzureOpenAI, OpenAI
import os
from dotenv import load_dotenv
from langchain.llms import LlamaCpp
load_dotenv()
from langchain import PromptTemplate, LLMChain
from langchain.callbacks.manager import CallbackManager
from langchain.callbacks.streaming_stdout import StreamingStdOutCallbackHandler


def get_llama_llm():
    n_gpu_layers = 1  # Metal set to 1 is enough.
    n_batch = 512  # Should be between 1 and n_ctx, consider the amount of RAM of your Apple Silicon Chip.
    
    # Callbacks support token-wise streaming
    callback_manager = CallbackManager([StreamingStdOutCallbackHandler()])
    llm = LlamaCpp(
        model_path="open-llama-7B-open-instruct.ggmlv3.q4_K_M.bin",
        n_gpu_layers=n_gpu_layers,
        n_batch=n_batch,
        n_ctx=4096,
        f16_kv=True,  # MUST set to True, otherwise you will run into problem after a couple of calls
        callback_manager=callback_manager,
        verbose=True,
        temperature=0.2,
        use_mmap=True
    )
    
    return llm

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
        'openai': get_openai_llm,
        'llama2': get_llama_llm
    }
    
    api_type = os.environ.get('OPENAI_API_TYPE')
    if api_type not in clients:
        raise ValueError(f"Invalid OPENAI_API_TYPE: {api_type}")
    
    return clients[api_type]()