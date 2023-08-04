from celery import shared_task
from api.data_sources.codebase_handler import codebase_handler
from api.data_sources.website_handler import website_handler
from api.data_sources.pdf_handler import pdf_handler
from web.workers.crawler import start_recursive_crawler

@shared_task
def pdf_handler_task(shared_folder, namespace):
    return pdf_handler(shared_folder=shared_folder, namespace=namespace)

@shared_task 
def website_handler_task(shared_folder, namespace):
    return website_handler(shared_folder=shared_folder, namespace=namespace)

@shared_task
def codebase_handler_task(repo_path, namespace):
    return codebase_handler(repo_path=repo_path, namespace=namespace)



@shared_task
def start_recursive_crawler_task(sender, data_source_id, chatbot_id):
    return start_recursive_crawler(data_source_id, chatbot_id)
