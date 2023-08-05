# Django Project with Celery Integration

Welcome to the exciting world of our Django project! This repository combines the power of Django and Celery to deliver a robust and efficient backend solution. Below, you'll find all the information you need to get started, run migrations, and utilize the project effectively.

## Table of Contents
- [Introduction](#introduction)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Project](#running-the-project)
- [Running Migrations](#running-migrations)
- [Environment Variables](#environment-variables)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

## Introduction

Our project combines Django, a powerful web framework, and Celery, a distributed task queue system. This combination allows for seamless handling of background tasks, ensuring a responsive user experience and efficient resource utilization.

## Prerequisites

Before you begin, make sure you have the following installed:

- Python (version x.y.z)
- Django (version x.y.z)
- Celery (version x.y.z)
- Other project-specific dependencies (see `requirements.txt`)

## Installation

1. Clone this repository:
   ```
   git clone https://github.com/your-username/your-project.git
   cd your-project
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

Here's a breakdown of the environment variables in the `.env` file:

- `OPENAI_API_TYPE`: Type of API (azure/openai)
- `OPENAI_API_BASE`: Base URL for the OpenAI API
- `OPENAI_API_KEY`: Your OpenAI API key
- `OPENAI_API_VERSION`: OpenAI API version
- `OPENAI_EMBEDDING_MODEL_NAME`: Name of the embedding model
- `OPENAI_DEPLOYMENT_NAME`: Name of the deployment
- `OPENAI_COMPLETION_MODEL`: Completion model (gpt-3.5-turbo)
- `EMBEDDING_PROVIDER`: Provider for embeddings (openai)
- `STORE`: Vector store (PINECONE/QDRANT)
- `PINECONE_API_KEY`: API key for Pinecone (if used)
- `PINECONE_ENV`: Pinecone environment (if used)
- `VECTOR_STORE_INDEX_NAME`: Index name for vector store (if used)
- `QDRANT_URL`: URL for Qdrant (if used)

## Troubleshooting

If you encounter issues related to forking on Mac M1, use the following flags before starting Celery:
```bash
export OBJC_DISABLE_INITIALIZE_FORK_SAFETY=YES
export DISABLE_SPRING=true
```

## Contributing

We welcome contributions! If you find any issues or want to enhance the project, please create a pull request.

## License

This project is licensed under the XYZ License - see the [LICENSE](LICENSE) file for details.

---

Thank you for choosing our project! If you have any questions or need further assistance, feel free to reach out to us.
