# Start from the official PHP 8.1 FPM image.
FROM php:8.2-fpm

# Set the working directory inside the container.
WORKDIR /var/www

# Install system dependencies and PHP extensions in a single RUN command
# to reduce the number of layers in the Docker image.
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_sqlite gd bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set ownership and permissions for the web root directory.
RUN chown -R www-data:www-data /var/www