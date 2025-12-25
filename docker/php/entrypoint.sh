#!/bin/bash
set -e

cd /usr/src/app

# Wait for database to be ready
echo "Waiting for database..."
until php artisan migrate:status 2>/dev/null || php artisan --version; do
  sleep 1
done

exec php-fpm