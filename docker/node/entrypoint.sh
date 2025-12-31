#!/bin/sh
set -e

cd /var/www/html/app

# Install dependencies if needed
if [ ! -d "node_modules" ]; then
  echo "Installing dependencies..."
  pnpm install
fi

# Keep container running indefinitely
exec tail -f /dev/null