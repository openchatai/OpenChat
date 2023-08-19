# Makefile to Run docker-compose for Django App

# Check if Docker is installed
DOCKER := $(shell command -v docker 2> /dev/null)
DOCKER_COMPOSE := $(shell command -v docker-compose 2> /dev/null)

ifndef DOCKER
$(error "Docker is not installed. Please install Docker before proceeding.")
endif

ifndef DOCKER_COMPOSE
$(error "Docker Compose is not installed. Please install Docker Compose before proceeding.")
endif

.env.docker:
	@echo "Error: The .env.docker file is missing. Please create it before proceeding. Refer example.env.docker or readme file in dj_backend_server/readme.md"
	exit 1

install: .env.docker
	docker-compose up -d

down:
	docker-compose down
