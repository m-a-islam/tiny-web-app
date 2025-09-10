#!/bin/sh

set -e

cd /var/www


if [ ! -f "vendor/autoload.php" ]; then
    echo "Running first-time setup..."

    if [ ! -f ".env" ]; then
        cp .env.example .env
        echo ".env file created."
    fi

    composer install --no-interaction --no-progress --prefer-dist
    echo "Composer dependencies installed."

    php artisan key:generate
    echo "Application key generated."
fi

chown -R www-data:www-data storage bootstrap/cache

php artisan migrate --force
echo "Database migrations executed."

exec "$@"