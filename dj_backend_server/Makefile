# Makefile to Run docker-compose for Django App

# Check if Docker and Docker Compose are installed
DOCKER := $(shell command -v docker 2> /dev/null)
DOCKER_COMPOSE := $(shell command -v docker-compose 2> /dev/null)
OS := $(shell uname)

ifndef DOCKER
$(error $(shell tput setaf 1)"Docker is not installed. Please install Docker before proceeding."$(shell tput sgr0))
endif

ifndef DOCKER_COMPOSE 
$(error $(shell tput setaf 1)"Docker Compose is not installed. Please install Docker Compose before proceeding."$(shell tput sgr0))
endif

.env.docker:
	@echo $(shell tput setaf 1)"Error: The .env.docker file is missing. Please create it before proceeding. Refer example.env.docker or readme file in dj_backend_server/readme.md"$(shell tput sgr0)
	exit 1

venv:
ifndef venv
	ifeq ($(OS), Darwin)
		@echo $(shell tput setaf 2)"Creating a virtual environment..."$(shell tput sgr0)
		python3 -m venv venv
	else
		@echo $(shell tput setaf 2)"Creating a virtual environment..."$(shell tput sgr0)
		python3 -m venv venv
	endif
endif

activate-venv: 
	@echo $$(tput setaf 3) "Activating virtual environment..." $$(tput sgr0)
	. venv/bin/activate


install-requirements: activate-venv
	@echo $$(tput setaf 2) "Installing Python dependencies..." $$(tput sgr0)
	pip install -r requirements.txt


install: .env install-requirements
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down --remove-orphans

ifeq ($(OS), Darwin)  # macOS
OPEN_COMMAND := open
else ifeq ($(OS), Linux)  
OPEN_COMMAND := xdg-open
else
OPEN_COMMAND := echo $(shell tput setaf 1)"Unsupported OS: $(OS)"$(shell tput sgr0)
endif

# Determine the architecture
ARCH := $(shell uname -m)
ifeq ($(ARCH),x86_64)
  COMPOSE_FILE := docker-compose.yaml
else
  COMPOSE_FILE := docker-compose.linux.yaml
endif

# celery -A dj_backend_server worker --loglevel=info &
dev-start:
	$(DOCKER_COMPOSE) -f docker-compose.linux.yaml up -d

	@echo $(shell tput setaf 3)"Waiting for 20 seconds before opening the browser..."$(shell tput sgr0)
	sleep 20

	$(OPEN_COMMAND) http://0.0.0.0:8000/	

dev-stop:
	$(DOCKER_COMPOSE) down --remove-orphans
	kill -9 $$(pgrep -f "celery -A dj_backend_server")
	kill -9 $$(pgrep -f "python3 manage.py runserver")

	@echo $$(tput setaf 3)"Services stopped."$$(tput sgr0)

force_migrate:
	@echo $(shell tput setaf 2)"Running migrations inside the Docker container..."
	$(DOCKER) exec -it web python manage.py makemigrations web
	$(DOCKER) exec -it web python manage.py migrate

all: venv install-requirements install down dev-start dev-stop
	@echo $$(tput setaf 3)"All steps executed successfully."$$(tput sgr0)
	
logs:
	$(DOCKER_COMPOSE) logs -f

exec:
	$(DOCKER) exec -u 0 -it oc_web /bin/bash

restart:
	$(DOCKER) restart oc_web
	$(DOCKER) restart oc_celery
	make logs

.PHONY: venv install-requirements install down dev-start dev-stop