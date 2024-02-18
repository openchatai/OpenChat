import os
from dotenv import load_dotenv
import logging.config
import traceback
from django.utils.timezone import make_aware
from datetime import datetime, timezone
from uuid import uuid4
from django.conf import settings
from langchain_openai.chat_models import ChatOpenAI
from langchain_community.chat_models import ChatOllama
from langchain_community.llms import AzureOpenAI
from langchain_community.llms import LlamaCpp
from langchain.callbacks.manager import CallbackManager
from langchain.callbacks.streaming_stdout import StreamingStdOutCallbackHandler
from web.models.failed_jobs import FailedJob

load_dotenv()
logging.config.dictConfig(settings.LOGGING)
logger = logging.getLogger(__name__)


def get_llama_llm():
    try:
        n_gpu_layers = 1  # Metal set to 1 is enough.
        n_batch = 512  # Should be between 1 and n_ctx, consider the amount of RAM of your Apple Silicon Chip.

        # Callbacks support token-wise streaming
        callback_manager = CallbackManager([StreamingStdOutCallbackHandler()])
        llm = LlamaCpp(
            model_path="llama-2-7b-chat.ggmlv3.q4_K_M.bin",
            n_gpu_layers=n_gpu_layers,
            n_batch=n_batch,
            n_ctx=4096,
            f16_kv=True,  # MUST set to True, otherwise you will run into problem after a couple of calls
            callback_manager=callback_manager,
            verbose=True,
            temperature=0.2,
        )

        return llm
    except Exception as e:

        logger.debug(f"Exception in get_llama_llm: {e}")
        failed_job = FailedJob(
            uuid=str(uuid4()),
            connection="default",
            queue="default",
            payload="get_llama_llm",
            exception=str(e),
            failed_at=make_aware(datetime.now(), timezone.utc),
        )
        failed_job.save()
        print(f"Exception occurred: {e}")
        traceback.print_exc()


# Azure OpenAI Language Model client
def get_azure_openai_llm():
    """Returns AzureOpenAI instance configured from environment variables"""
    try:
        openai_api_type = os.environ["AZURE_OPENAI_API_TYPE"]
        openai_api_key = os.environ["AZURE_OPENAI_API_KEY"]
        openai_deployment_name = os.environ["AZURE_OPENAI_DEPLOYMENT_NAME"]
        openai_model_name = os.environ["AZURE_OPENAI_COMPLETION_MODEL"]
        openai_api_version = os.environ["AZURE_OPENAI_API_VERSION"]
        openai_api_base = os.environ["AZURE_OPENAI_API_BASE"]
        openai_api_key = os.environ["AZURE_OPENAI_API_KEY"]
        openai_deployment_name = os.environ["AZURE_OPENAI_DEPLOYMENT_NAME"]
        openai_model_name = os.environ["AZURE_OPENAI_COMPLETION_MODEL"]
        openai_api_version = os.environ["AZURE_OPENAI_API_VERSION"]
        openai_api_base = os.environ["AZURE_OPENAI_API_BASE"]
        return AzureOpenAI(
            openai_api_base=openai_api_base,
            openai_api_key=openai_api_key,
            deployment_name=openai_deployment_name,
            model_name=openai_model_name,
            openai_api_type=openai_api_type,
            openai_api_version=openai_api_version,
            temperature=0,
            batch_size=8,
        )
    except Exception as e:
        logger.debug(f"Exception in get_azure_openai_llm: {e}")
        failed_job = FailedJob(
            uuid=str(uuid4()),
            connection="default",
            queue="default",
            payload="get_azure_openai_llm",
            exception=str(e),
            failed_at=make_aware(datetime.now(), timezone.utc),
        )
        failed_job.save()
        print(f"Exception occurred: {e}")
        traceback.print_exc()


# OpenAI Language Model client
def get_openai_llm():
    """Returns OpenAI instance configured from environment variables"""
    try:
        openai_api_key = os.environ.get("OPENAI_API_KEY")
        temperature = os.environ.get("OPENAI_API_TEMPERATURE")
        model = os.environ.get("OPENAI_API_MODEL", "gpt-3.5-turbo")

        logging.debug(
            f"We are in get_openai_llm: {openai_api_key} {temperature} {model}"
        )
        return ChatOpenAI(
            temperature=temperature,
            openai_api_key=openai_api_key,
            model=model,
        )
    except Exception as e:
        logger.debug(f"Exception in get_openai_llm: {e}")
        failed_job = FailedJob(
            uuid=str(uuid4()),
            connection="default",
            queue="default",
            payload="get_openai_llm",
            exception=str(e),
            failed_at=make_aware(datetime.now(), timezone.utc),
        )
        failed_job.save()
        print(f"Exception occurred: {e}")
        traceback.print_exc()


def get_ollama_llm():
    """Returns an Ollama instance configured from environment variables"""
    try:
        base_url = os.environ.get("OLLAMA_URL")
        model = os.environ.get("OLLAMA_MODEL_NAME", "llama2")

        llm = ChatOllama(
            base_url=base_url,
            model=model,
            callback_manager=CallbackManager([StreamingStdOutCallbackHandler()]),
        )
        return llm

    except Exception as e:
        logger.debug(f"Exception in get_ollama_llm: {e}")
        failed_job = FailedJob(
            uuid=str(uuid4()),
            connection="default",
            queue="default",
            payload="get_ollama_llm",
            exception=str(e),
            failed_at=make_aware(datetime.now(), timezone.utc),
        )
        failed_job.save()
        print(f"Exception occurred: {e}")
        traceback.print_exc()


def get_llm():
    """Returns LLM client instance based on OPENAI_API_TYPE"""
    try:
        clients = {
            "azure": get_azure_openai_llm,
            "openai": get_openai_llm,
            "llama2": get_llama_llm,
            "ollama": lambda: get_ollama_llm(),
        }

        # DEVENV
        # if settings.DEBUG:
        #     api_type = "ollama"
        api_type = os.environ.get("OPENAI_API_TYPE", "openai")

        if api_type not in clients:
            raise ValueError(f"Invalid OPENAI_API_TYPE: {api_type}")

        logging.debug(f"Using LLM: {api_type}")

        if api_type in clients:
            llm_instance = clients[api_type]()
            if llm_instance is None:
                logger.error(f"LLM instance for {api_type} could not be created.")
                return None
            return llm_instance
        else:
            raise ValueError(f"Invalid OPENAI_API_TYPE: {api_type}")

    except Exception as e:
        failed_job = FailedJob(
            uuid=str(uuid4()),
            connection="default",
            queue="default",
            payload="get_llm",
            exception=str(e),
            failed_at=make_aware(datetime.now(), timezone.utc),
        )
        failed_job.save()  # Ensure datetime is timezone-aware
        print(f"Exception occurred in get_llm: {e}")
        traceback.print_exc()
