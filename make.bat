DOCKER_COMPOSE = docker-compose
BUILD_OPTS =


install:
	@echo "=== Putting the services down (if already running) ==="
	${DOCKER_COMPOSE} down --remove-orphans

	@echo "=== Setting up Docker environment ==="
	@echo "=== This will overwrite your .env files, you still have some time to abort ==="
	@echo "=== Copying .env files ==="
	copy /Y backend-server\.env.example backend-server\.env
	copy /Y common.env llm-server\.env
	${DOCKER_COMPOSE} build ${BUILD_OPTS}
	${DOCKER_COMPOSE} up -d
	@echo "=== Waiting for services to start (~20 seconds) ==="

	@echo "=== Clearing backend server config cache ==="
	${DOCKER_COMPOSE} exec backend-server php artisan config:cache
    ${DOCKER_COMPOSE} exec backend-server php artisan cache:clear
    ${DOCKER_COMPOSE} exec backend-server php artisan config:cache

	@echo "=== Run backend server server migrations ==="
	${DOCKER_COMPOSE} exec backend-server php artisan migrate --seed

	@echo "=== Running backward compatibility scripts ==="
	${DOCKER_COMPOSE} exec backend-server php artisan storage:link
	${DOCKER_COMPOSE} exec backend-server php artisan prompt:fill



	${DOCKER_COMPOSE} run -d backend-server php artisan queue:work --timeout=200

	@echo "=== Installation completed ==="
	@echo "=== 🔥🔥 You can now access the dashboard at -> http://localhost:8000 ==="
	@echo "=== Enjoy! ==="

run-worker:
	${DOCKER_COMPOSE} exec backend-server php artisan queue:work --timeout=200

db-setup:
	${DOCKER_COMPOSE} exec backend-server php artisan migrate:fresh --seed

down:
	${DOCKER_COMPOSE} down --remove-orphans

exec-backend-server:
	${DOCKER_COMPOSE} exec backend-server bash
.PHONY: install down