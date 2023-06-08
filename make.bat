@echo off
SET DOCKER_COMPOSE=docker-compose

REM Colors
SET COLOR_RESET=
SET COLOR_BOLD=
SET COLOR_GREEN=
SET COLOR_YELLOW=

REM Targets
:install
echo %COLOR_BOLD%=== Putting the services down (if already running) ===%COLOR_RESET%
%DOCKER_COMPOSE% down --remove-orphans

echo %COLOR_BOLD%=== Setting up Docker environment ===%COLOR_RESET%
REM Copy .env.example to .env for backend-server
REM Show warning before continue, and wait for 10 seconds
echo %COLOR_BOLD%=== This will overwrite your .env files, you still have some time to abort ===%COLOR_RESET%
timeout /t 10 > NUL
echo %COLOR_BOLD%=== Copying .env files ===%COLOR_RESET%
copy /y backend-server.env.example backend-server.env > NUL 2>&1 || (echo. & echo File already exists.)
copy /y common.env llm-server.env > NUL 2>&1 || (echo. & echo File already exists.)
%DOCKER_COMPOSE% build
%DOCKER_COMPOSE% up -d
echo %COLOR_BOLD%=== Waiting for services to start (~20 seconds) ===%COLOR_RESET%
timeout /t 20 > NUL

echo %COLOR_BOLD%=== Clearing backend server config cache ===%COLOR_RESET%
%DOCKER_COMPOSE% exec backend-server php artisan config:cache

echo %COLOR_BOLD%=== Run backend server migrations ===%COLOR_RESET%
%DOCKER_COMPOSE% exec backend-server php artisan migrate --seed
%DOCKER_COMPOSE% exec backend-server php artisan storage:link
%DOCKER_COMPOSE% run -d backend-server php artisan queue:work --timeout=200

echo %COLOR_BOLD%=== Installation completed ===%COLOR_RESET%
echo %COLOR_BOLD%=== 🔥🔥 You can now access the dashboard at -> http://localhost:8000 ===%COLOR_RESET%
echo %COLOR_BOLD%=== Enjoy! ===%COLOR_RESET%
goto :EOF

:run-worker
%DOCKER_COMPOSE% exec backend-server php artisan queue:work --timeout=200
goto :EOF

:db-setup
%DOCKER_COMPOSE% exec backend-server php artisan migrate:fresh --seed
goto :EOF

:down
%DOCKER_COMPOSE% down --remove-orphans
goto :EOF

:exec-backend-server
%DOCKER_COMPOSE% exec backend-server bash
goto :EOF