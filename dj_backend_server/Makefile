# Makefile to Run docker-compose for Django App

# first run "make preinstall" to create .env file and venv environment
# you will need to edit .env file, it will be created from example.env
# after .env is done, run "make install" to create run the nginx and docker-compose. 
# doublecheck if the nginx.conf is correct, if not, edit it and run "make install" again
# if you want to run the app in development mode, run "make dev-start"
# to stop the app, run "make dev-stop"
# to run migrations, run "make force_migrate"
# to see the logs, run "make logs"
# to enter the python container, run "make exec"
# to restart the python and celery containers, run "make restart"
# to stop the app, run "make down" (in dev mode, run "make dev-stop")

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

.env:
	@if [ ! -f .env ]; then \
		echo "\033[1;31mError: The .env file is missing.\033[0m"; \
		if [ -f example.env ]; then \
			echo "An example.env file has been found."; \
			read -p "Do you want to copy example.env to .env? [y/N] " yn; \
			case $$yn in \
				[Yy]*) cp example.env .env; \
				       echo "The .env file has been created from example.env."; \
				       echo "Please edit the .env file and set the values for OPENAI_API_KEY and APP_URL.";; \
				*) echo "Exiting. Please create a .env file manually."; exit 1;; \
			esac; \
		else \
			echo "No example.env file found. Please create a .env file manually."; \
			exit 1; \
		fi; \
	fi

venv:
	@if [ ! -d "venv" ]; then \
		echo "\033[1;32mCreating a virtual environment...\033[0m"; \
		python3 -m venv venv; \
	else \
		echo "Virtual environment already exists."; \
	fi

install-requirements: venv
	@echo "Installing requirements..."
	@venv/bin/pip install -r requirements.txt

nginx:
	@if [ ! -f .env ]; then \
		echo $$(tput setaf 1)"Error: .env file not found. Please create it before proceeding."$$(tput sgr0); \
		exit 1; \
	fi
	@export APP_URL=$$(grep APP_URL .env | cut -d '=' -f2- | sed -e "s#http[s]\?://##" -e "s#'##g"); \
	if [ -z "$$APP_URL" ]; then \
		echo $$(tput setaf 1)"Error: APP_URL is not set in .env. Please set it before proceeding."$$(tput sgr0); \
		exit 1; \
	fi
	@if [ ! -f nginx/nginx.conf ]; then \
		envsubst '$${APP_URL}' < nginx/nginx.template.conf > nginx/nginx.conf; \
		echo $$(tput setaf 2) "Installing NGINX conf file with APP_URL $$APP_URL"$$(tput sgr0); \
	fi

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

install: nginx
	$(DOCKER_COMPOSE) up -d

pre-install: .env venv install-requirements

# celery -A dj_backend_server worker --loglevel=info &
dev-start:
	$(DOCKER_COMPOSE) -f docker-compose.linux.yaml up -d
	@echo "$(shell tput setaf 3)Waiting for 20 seconds before opening the browser...$(shell tput sgr0)"
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
	
logs:
	$(DOCKER_COMPOSE) logs -f

exec:
	$(DOCKER) exec -u 0 -it oc_web /bin/bash

restart:
	$(DOCKER) restart oc_web
	$(DOCKER) restart oc_celery
	make logs

.PHONY: install .env venv activate-venv install-requirements down dev-start dev-stop nginx
