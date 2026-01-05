#!/bin/sh
set -e

while true; do
  echo "Waiting for backend..."
  until nc -z backend 9000; do
    sleep 1
  done

  echo "Backend up, starting nginx..."
  nginx -g 'daemon off;'

  echo "Backend stopped, exiting nginx..."
  sleep 1
done