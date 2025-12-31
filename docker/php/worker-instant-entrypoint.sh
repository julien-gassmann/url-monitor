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

echo "Starting instant queue worker..."
exec php artisan queue:work \
  redis_instant \
  --verbose \
  --queue=instant \
  --tries=1 \
  --timeout=60 \
  --sleep=0 \
  --max-jobs=1000