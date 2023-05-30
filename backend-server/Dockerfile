# Use an official PHP runtime as the base image
FROM php:8.1-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git

RUN docker-php-ext-install pdo pdo_mysql

# Set the working directory
WORKDIR /var/www/html

# Copy the project files to the container
COPY . .

# Copy the .env file (assuming it's in the same directory as the Dockerfile)
COPY .env .env

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Composer dependencies
RUN composer install --no-plugins --no-scripts

# Generate the Laravel application key
RUN php artisan key:generate

# Expose the container's port 8000 (default for `php artisan serve`)
EXPOSE 8000

# Start the Laravel development server
CMD php artisan serve --host=0.0.0.0 --port=8000
