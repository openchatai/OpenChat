# Build options
$env:DOCKER_COMPOSE = "docker-compose"
$env:BUILD_OPTS = ""

# Install command
Write-Host "=== Putting the services down (if already running) ==="
& $env:DOCKER_COMPOSE down --remove-orphans

Write-Host "=== Setting up Docker environment ==="
Write-Host "=== This will overwrite your .env files, you still have some time to abort ==="
Write-Host "=== Copying .env files ==="
Copy-Item -Path .\backend-server\.env.example -Destination .\backend-server\.env -Force
Copy-Item -Path .\common.env -Destination .\llm-server\.env -Force
& $env:DOCKER_COMPOSE build $env:BUILD_OPTS
& $env:DOCKER_COMPOSE up -d
Write-Host "=== Waiting for services to start (~30 seconds) ==="
Start-Sleep -Seconds 30

# Clear backend server config cache
Write-Host "=== Clearing backend server config cache ==="
& $env:DOCKER_COMPOSE exec backend-server php artisan config:cache
& $env:DOCKER_COMPOSE exec backend-server php artisan cache:clear
& $env:DOCKER_COMPOSE exec backend-server php artisan config:cache

# Run backend server migrations
Write-Host "=== Run backend server server migrations ==="
& $env:DOCKER_COMPOSE exec backend-server php artisan migrate --seed

# Running backward compatibility scripts
Write-Host "=== Running backward compatibility scripts ==="
& $env:DOCKER_COMPOSE exec backend-server php artisan storage:link
& $env:DOCKER_COMPOSE exec backend-server php artisan prompt:fill

# Running queue worker
Write-Host "=== Running queue worker ==="
& $env:DOCKER_COMPOSE run -d backend-server php artisan queue:work --timeout=200

Write-Host "=== Installation completed ==="
Write-Host "=== 🔥🔥 You can now access the dashboard at -> http://localhost:8000 ==="
Write-Host "=== Enjoy! ==="

# Other Commands
# Starting Docker containers
Write-Host "=== Starting Docker containers ==="
& $env:DOCKER_COMPOSE up -d
Write-Host "=== Waiting for services to start (~30 seconds) ==="
Start-Sleep -Seconds 30

# Run queue worker
Write-Host "=== Running queue worker ==="
& $env:DOCKER_COMPOSE exec backend-server php artisan queue:work --timeout=200

# Database setup
Write-Host "=== Running database setup ==="
& $env:DOCKER_COMPOSE exec backend-server php artisan migrate:fresh --seed

# Stop services
Write-Host "=== Stopping Docker containers ==="
& $env:DOCKER_COMPOSE down --remove-orphans

# Execute backend server
Write-Host "=== Executing backend server ==="
& $env:DOCKER_COMPOSE exec backend-server bash
