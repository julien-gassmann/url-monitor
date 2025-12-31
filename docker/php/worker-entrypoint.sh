#!/bin/sh
set -e

cd /usr/src/app

echo "Waiting for database..."
until php artisan migrate:status >/dev/null 2>&1; do
  sleep 1
done

echo "Waiting for redis..."
until redis-cli -h redis ping >/dev/null 2>&1; do
  sleep 1
done

echo "Starting queue worker..."
exec php artisan queue:work \
  --verbose \
  --tries=3 \
  --timeout=60 \
  --sleep=3 \
  --max-jobs=1000