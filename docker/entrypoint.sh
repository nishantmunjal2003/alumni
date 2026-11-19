#!/bin/sh
set -e

# Ensure system and application directories exist
mkdir -p /var/log/supervisor \
         /run/nginx \
         /var/www/html/storage/app/public \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

# If .env does not exist, copy from .env.example
if [ ! -f /var/www/html/.env ]; then
    if [ -f /var/www/html/.env.example ]; then
        cp /var/www/html/.env.example /var/www/html/.env
    fi
fi

# Ensure Laravel storage symlink exists
php artisan storage:link --force || true

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/storage 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

exec "$@"
