#!/bin/sh
set -e

cd /usr/src/app

echo "Waiting for database..."
until php artisan migrate:status >/dev/null 2>&1; do
  sleep 1
done

echo "Starting PHP-FPM..."
exec php-fpm