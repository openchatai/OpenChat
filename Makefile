# Variables
DOCKER_COMPOSE = docker-compose

# Colors
COLOR_RESET = \033[0m
COLOR_BOLD = \033[1m
COLOR_GREEN = \033[32m
COLOR_YELLOW = \033[33m

# Check if Docker is installed
DOCKER_INSTALLED := $(shell command -v docker-compose 2> /dev/null)

# Targets
install:
    ifndef DOCKER_INSTALLED
	    $(error Docker is not installed. Please visit https://www.docker.com/get-started to download and install Docker.)
    endif

	@echo "$(COLOR_BOLD)=== Putting the services down (if already running) ===$(COLOR_RESET)"
	$(DOCKER_COMPOSE) down --remove-orphans

	@echo "$(COLOR_BOLD)=== Setting up Docker environment ===$(COLOR_RESET)"
    # Copy .env.example to .env for backend-server
    # Show warning before continue, and wait for 10 seconds
	@echo "$(COLOR_BOLD)=== This will overwrite your .env files, you still have some time to abort ===$(COLOR_RESET)"
	@sleep 5
	@echo "$(COLOR_BOLD)=== Copying .env files ===$(COLOR_RESET)"
	cp -n backend-server/.env.example backend-server/.env 2>/dev/null || true
	cp common.env llm-server/.env 2>/dev/null || true
	$(DOCKER_COMPOSE) build #--no-cache
	$(DOCKER_COMPOSE) up -d #--force-recreate
	@echo "$(COLOR_BOLD)=== Waiting for services to start (~20 seconds) ===$(COLOR_RESET)"
	@sleep 20

	@echo "$(COLOR_BOLD)=== Clearing backend server config cache ===$(COLOR_RESET)"
	$(DOCKER_COMPOSE) exec backend-server php artisan cache:clear
	$(DOCKER_COMPOSE) exec backend-server php artisan config:cache

	@echo "$(COLOR_BOLD)=== Run backend server migrations ===$(COLOR_RESET)"
	$(DOCKER_COMPOSE) exec backend-server php artisan migrate --seed
	$(DOCKER_COMPOSE) exec backend-server php artisan storage:link

	@echo "$(COLOR_BOLD)=== Running backward compatibility scripts ===$(COLOR_RESET)"
	$(DOCKER_COMPOSE) exec backend-server php artisan prompt:fill

	$(DOCKER_COMPOSE) run -d backend-server php artisan queue:work --timeout=200

	@echo "$(COLOR_BOLD)=== Installation completed ===$(COLOR_RESET)"
	@echo "$(COLOR_BOLD)=== 🔥🔥 You can now access the dashboard at -> http://localhost:8000 ===$(COLOR_RESET)"
	@echo "$(COLOR_BOLD)=== Enjoy! ===$(COLOR_RESET)"

run-worker:
	$(DOCKER_COMPOSE) exec backend-server php artisan queue:work --timeout=200

db-setup:
	$(DOCKER_COMPOSE) exec backend-server php artisan migrate:fresh --seed

down:
	$(DOCKER_COMPOSE) down --remove-orphans

exec-backend-server:
	$(DOCKER_COMPOSE) exec backend-server bash
.PHONY: install down