#!/bin/sh
set -e

cd /usr/src/app

echo "Waiting for database..."
until php artisan migrate:status >/dev/null 2>&1; do
  sleep 1
done

echo "Starting scheduler..."
exec sh -c '
while true; do
  php artisan schedule:run --verbose --no-interaction
  sleep 60
done
'