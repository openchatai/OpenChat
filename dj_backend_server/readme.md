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

## Environment Variables in `.env` File

Below is a breakdown of the environment variables found within the `.env` file:

- `OPENAI_API_TYPE`: Specifies the type of API (either 'azure' or 'openai').
- `OPENAI_API_BASE`: The base URL for the OpenAI API.
- `OPENAI_API_KEY`: Your unique OpenAI API key.
- `OPENAI_API_VERSION`: The version of the OpenAI API.
- `OPENAI_EMBEDDING_MODEL_NAME`: The name of the embedding model being used.
- `OPENAI_DEPLOYMENT_NAME`: The designated deployment name.
- `OPENAI_COMPLETION_MODEL`: The specific completion model in use (e.g., 'gpt-3.5-turbo').
- `EMBEDDING_PROVIDER`: The provider chosen for embeddings (typically 'openai').
- `STORE`: The vector store option (PINECONE or QDRANT).
- `PINECONE_API_KEY`: API key for Pinecone, if applicable.
- `PINECONE_ENV`: Pinecone environment identifier, if used.
- `VECTOR_STORE_INDEX_NAME`: The name assigned to the vector store index, if applicable.
- `QDRANT_URL`: The URL for Qdrant, if utilized.

- `CELERY_BROKER_URL`: Redis broker URL for Celery (e.g., `redis://localhost:6379/0`).
- `CELERY_RESULT_BACKEND`: Redis backend for Celery (e.g., `redis://localhost:6379/0`).
- `DATABASE_NAME`: Name of the database.
- `DATABASE_USER`: Username for database access.
- `DATABASE_PASSWORD`: Password for database access.
- `DATABASE_HOST`: Hostname of the database (usually 'localhost' in this context).
- `DATABASE_PORT`: Port number for database connection (e.g., `3306`).

These environment variables configure your application's settings, interactions with external services, and database connectivity. Make sure to adjust them as needed to suit your project's requirements.


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

## Troubleshooting

If you encounter issues related to forking on Mac M1, use the following flags before starting Celery:
```bash
export OBJC_DISABLE_INITIALIZE_FORK_SAFETY=YES
export DISABLE_SPRING=true
```

## Contributing

We welcome contributions! If you find any issues or want to enhance the project, please create a pull request.


Download llama2-7b from https://huggingface.co/TheBloke/Llama-2-7B-Chat-GGML/tree/main

install the correct llama-python-cpp from this page https://python.langchain.com/docs/integrations/llms/llamacpp