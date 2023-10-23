# Django Project with Celery Integration

Welcome to the exciting world of our Django project! This repository combines the power of Django and Celery to deliver a robust and efficient backend solution. Below, you'll find all the information you need to get started, run migrations, and utilize the project effectively.

## Table of Contents
- [Introduction](#introduction)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Project](#running-the-project)
- [Dockerizing Project Execution](#running-the-project-docker)
- [Running Migrations](#running-migrations)
- [Environment Variables](#environment-variables)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

## Introduction

Our project combines Django, a powerful web framework, and Celery, a distributed task queue system. This combination allows for seamless handling of background tasks, ensuring a responsive user experience and efficient resource utilization.

## Prerequisites

Before you begin, make sure you have the following installed:
- project-specific dependencies (see `requirements.txt`)

## Installation

1. Clone this repository:
   ```
   git clone https://github.com/openchatai/OpenChat.git
   cd dj_backend_server
   ```

2. Create a virtual environment:
   ```
   python3 -m venv venv
   source venv/bin/activate
   ```

3. Install dependencies:
   ```
   pip install -r requirements.txt
   ```

## Common Issues

If database migrations fail to run for any reason, you can use the following command to re-run the migrations within the Docker container:

```bash
make force_migrate
```

### Running Migrations in Docker Container

To address migration issues within the Docker container, a new Makefile target named `force_migrate` has been introduced. This target allows you to forcefully re-run the migrations, which can be useful in resolving migration-related problems.

### Conditional Docker Compose Files

The Makefile now includes logic to dynamically select the appropriate Docker Compose file based on the underlying system architecture. This decision is made between two options: `docker-compose.yaml` for non-Linux environments and `docker-compose.linux.yaml` for Linux systems. This ensures that the correct Docker Compose configuration is utilized according to the specific environment in use.

## Running Migrations

To run migrations inside the Docker container, you can use the following command:

```bash
make migrate

## Configuration

Before running the project, you need to configure your environment variables. Rename the `.env.example` file to `.env` and fill in the necessary values for your environment.

## Running the Project

To start the Django development server along with Celery:

```bash
python manage.py runserver
```

To start the Celery worker:

```bash
export OBJC_DISABLE_INITIALIZE_FORK_SAFETY=YES
export DISABLE_SPRING=true
celery -A dj_backend_server worker --loglevel=info
```

## Running Migrations

To apply database migrations:

```bash
python manage.py migrate
```

## Environment Variables

Certainly, here's the rephrased content with a consistent and user-friendly style:

Here's an improved version of your markdown:

# Environment Variables in the `.env` File

This section provides a detailed breakdown of the environment variables defined within the `.env` file. These variables are crucial for configuring your application and services.

## Common Configuration Variables

- `OPENAI_API_TYPE`: Specifies the type of API, which can be set to either 'azure' or 'openai'.
- `OPENAI_API_BASE`: The base URL for the OpenAI API.
- `OPENAI_API_KEY`: Your unique OpenAI API key.
- `OPENAI_API_VERSION`: The version of the OpenAI API in use.
- `OPENAI_EMBEDDING_MODEL_NAME`: The name of the specific embedding model being utilized.
- `OPENAI_DEPLOYMENT_NAME`: The designated deployment name.
- `OPENAI_COMPLETION_MODEL`: The exact completion model in use, e.g., 'gpt-3.5-turbo'.
- `EMBEDDING_PROVIDER`: The chosen provider for embeddings, typically set to 'openai'.
- `STORE`: The vector store option, which can be either 'PINECONE' or 'QDRANT'.
- `PINECONE_API_KEY`: API key for Pinecone, if applicable.
- `PINECONE_ENV`: Pinecone environment identifier, if used.
- `VECTOR_STORE_INDEX_NAME`: The name assigned to the vector store index, if applicable.
- `QDRANT_URL`: The URL for Qdrant, if it's part of your setup.

## Celery Configuration (Optional)

- `CELERY_BROKER_URL`: Redis broker URL for Celery (e.g., `redis://localhost:6379/0`).
- `CELERY_RESULT_BACKEND`: Redis backend for Celery (e.g., `redis://localhost:6379/0`).

## Database Configuration

- `DATABASE_NAME`: Name of the database.
- `DATABASE_USER`: Username for database access.
- `DATABASE_PASSWORD`: Password for database access.
- `DATABASE_HOST`: Hostname of the database, usually 'localhost' in this context.
- `DATABASE_PORT`: Port number for the database connection (e.g., `3306`).

## Application Settings

- `ALLOWED_HOSTS`: A comma-separated list of allowed hostnames, IP addresses, and domains.
- `APP_URL`: The URL of your application or service.
- `PDF_LIBRARY`: Specifies the PDF library in use, usually set to 'external'.
- `OCR_LICCODE`: License code for OCR, e.g., 'XXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXX'.
- `OCR_USERNAME`: OCR service username.
- `OCR_LANGUAGE`: The language setting for OCR, often set to 'english'.

## User Authentication (Optional)

- `DJANGO_SUPERUSER_USERNAME`: Superuser username, typically 'admin'.
- `DJANGO_SUPERUSER_PASSWORD`: Superuser password, commonly 'admin_password'.

Please make sure to set these environment variables according to your project's specific requirements.

These environment variables configure your application's settings, interactions with external services, and database connectivity.

## User creation

Create a Superuser. Login into docker 

```bash
docker exec -it web /bin/bash
```

and execute command 

```bash
python manage.py createsuperuser
```

This will prompt you to enter a username, email, and password for the superuser. After this, you can use username and password to login 
`APP_URL` : 'http://URL-OF-DOMAIN'

## Dockerizing Project Execution

To streamline the project execution process, Docker proves useful. Instead of using the typical `.env` file, you will utilize an `.env.docker` file. This `.env.docker` file should encapsulate the same set of credentials and steps as elucidated in the preceding section. 

However, do note that a few configurations will undergo modification. Specifically, instances of 'localhost' should be replaced with the respective names of the containers. Here's how the modified configurations should appear:

```yaml
---
CELERY_BROKER_URL=redis://redis:6379/0
CELERY_RESULT_BACKEND=redis://redis:6379/0
DATABASE_NAME=mydb
DATABASE_USER=myuser
DATABASE_PASSWORD=mypassword
DATABASE_HOST=mysql
DATABASE_PORT=3306
```

## External PDF library

You can now configure the .env.docker file to use an external PDF library. By default, the internal library is used. However, the external library offers several advantages, such as Optical Character Recognition (OCR) capabilities and support for various PDF file types. The external service provides 25 free pages per day, or you can subscribe for additional pages at a reasonable cost. If you want to use it, visit their website and create a new account via http://www.ocrwebservice.com/account/signup and get license code.

## Troubleshooting

If you encounter issues related to forking on Mac M1, use the following flags before starting Celery:
```bash
export OBJC_DISABLE_INITIALIZE_FORK_SAFETY=YES
export DISABLE_SPRING=true
```

[Building with docker guide](docs/building_with_docker.md)

## Todo list
[List of todo items](docs/todo.md)

## Contributing

We welcome contributions! If you find any issues or want to enhance the project, please create a pull request.


Download llama2-7b from https://huggingface.co/TheBloke/Llama-2-7B-Chat-GGML/tree/main

install the correct llama-python-cpp from this page https://python.langchain.com/docs/integrations/llms/llamacpp