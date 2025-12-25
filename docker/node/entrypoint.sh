#!/bin/sh
set -e

cd /app

# Install dependencies if needed
if [ ! -d "node_modules" ]; then
  echo "Installing dependencies..."
  pnpm install
fi

# Start dev server
echo "Starting development server..."
exec pnpm dev --host