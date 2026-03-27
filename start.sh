#!/bin/bash
echo "=== PWD ==="
pwd
echo "=== LS ==="
ls -la .
echo "=== FRONTEND ==="
ls -la frontend/.next/ || echo "frontend/.next/ introuvable"

cd /app/backend && php-fpm -F &
cd /app
node /app/frontend/.next/standalone/server.js