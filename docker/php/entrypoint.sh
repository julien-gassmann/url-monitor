#!/bin/bash
set -e

cd /usr/src/app

# Wait for database to be ready
until php artisan migrate:status 2>/dev/null; do
  sleep 1
done

# Start Laravel cron scheduler
(
  while true; do
    php artisan schedule:run --verbose --no-interaction
    sleep 60;
  done
) &

# Start queue worker
php artisan queue:work --verbose --tries=3 --timeout=60 &

# Start PHP-FPM
exec php-fpm