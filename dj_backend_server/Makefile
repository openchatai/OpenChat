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
ifndef VIRTUAL_ENV
	ifeq ($(OS), Darwin)
		@echo $(shell tput setaf 2)"Creating a virtual environment..."$(shell tput sgr0)
		python3 -m venv venv
	else
		@echo $(shell tput setaf 2)"Creating a virtual environment..."$(shell tput sgr0)
		python3 -m venv venv
	endif
endif

activate-venv: 
	@echo $(shell tput setaf 3)"Activating virtual environment..."$(shell tput sgr0)
	. venv/bin/activate


install-requirements: activate-venv
	@echo $(shell tput setaf 2)"Installing Python dependencies..."$(shell tput sgr0)
	pip install -r requirements.txt

install: .env install-requirements
	docker-compose up -d

down:
	docker-compose down

ifeq ($(OS), Darwin)  # macOS
OPEN_COMMAND := open
else ifeq ($(OS), Linux)  
OPEN_COMMAND := xdg-open
else
OPEN_COMMAND := echo $(shell tput setaf 1)"Unsupported OS: $(OS)"$(shell tput sgr0)
endif

dev-start: 
	docker-compose up -d
	venv/bin/celery -A dj_backend_server worker --loglevel=info &
	venv/bin/python manage.py sync_models && venv/bin/python manage.py runserver 0.0.0.0:8000 &

	@echo $(shell tput setaf 3)"Waiting for 20 seconds before opening the browser..."$(shell tput sgr0)
	sleep 20

	$(OPEN_COMMAND) http://localhost:8000

dev-stop:
	docker-compose down
	kill -9 $$(pgrep -f "celery -A dj_backend_server")
	kill -9 $$(pgrep -f "python manage.py runserver")

	@echo $$(tput setaf 3)"Services stopped."$$(tput sgr0)

.PHONY: venv install-requirements install down dev-start dev-stop