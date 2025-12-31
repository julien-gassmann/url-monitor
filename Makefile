# =========================
# Variables
# =========================
DC = docker compose
BACKEND = backend
FRONTEND = frontend

# =========================
# Help
# =========================
.PHONY: help
help:
	@echo ""
	@echo "Available commands:"
	@echo ""
	@echo "  make setup           → Full project setup"
	@echo "  make build           → Build docker images"
	@echo "  make up              → Start containers in background"
	@echo "  make down            → Stop containers"
	@echo "  make restart         → Restart containers"
	@echo "  make logs            → Show all logs"
	@echo ""
	@echo "  make backend         → Shell inside backend container"
	@echo "  make backend-install → Install backend dependencies (composer, .env, key)"
	@echo "  make backend-check   → Run composer check in backend"
	@echo "  make migrate         → Run Laravel migrations"
	@echo "  make db-refresh      → Run fresh Laravel migrations"
	@echo ""
	@echo "  make frontend        → Shell inside frontend container"
	@echo "  make frontend-install→ Install frontend dependencies (pnpm, .env)"
	@echo "  make frontend-dev    → Start Next.js dev server"
	@echo ""
	@echo "  make emails          → Build MJML emails"
	@echo ""
	@echo "  make clean           → Remove containers and node_modules/vendor"
	@echo "  make rebuild         → Clean + build + up"
	@echo ""

# =========================
# Global docker commands
# =========================
.PHONY: build up down restart logs

build:
	$(DC) build

up:
	$(DC) up -d

down:
	$(DC) down

restart:
	$(DC) restart

logs:
	$(DC) logs -f

# =========================
# Setup (one-shot)
# =========================
.PHONY: setup
setup: build up wait-services backend-install migrate frontend-install emails
	@echo ""
	@echo "✅ Project is ready!"
	@echo "   Backend:  http://localhost:8080"
	@echo "   Frontend: http://localhost:3000"
	@echo ""
	@echo "Start dev server: make frontend-dev"
	@echo ""

# Wait for services to be healthy
.PHONY: wait-services
wait-services:
	@echo "⏳ Waiting for services to be ready..."
	@until $(DC) exec -T db healthcheck.sh --connect --innodb_initialized 2>/dev/null; do \
		echo "  Waiting for database..."; \
		sleep 2; \
	done
	@echo "✅ Database is ready"
	@until $(DC) exec -T redis redis-cli ping 2>/dev/null | grep -q PONG; do \
		echo "  Waiting for Redis..."; \
		sleep 1; \
	done
	@echo "✅ Redis is ready"
	@sleep 2
	@echo "✅ All services are ready"

# =========================
# Backend
# =========================
.PHONY: backend-install backend migrate

backend-install:
	@echo "📦 Installing backend dependencies..."
	$(DC) exec $(BACKEND) composer install
	$(DC) exec $(BACKEND) php artisan key:generate --force
	$(DC) exec $(BACKEND) sh -c '[ ! -f .env ] && cp .env.example .env || echo "✅ .env already exists"'
	@echo "✅ Backend dependencies installed"

backend:
	$(DC) exec $(BACKEND) bash

backend-check:
	$(DC) exec $(BACKEND) composer check

migrate:
	@echo "🔄 Running migrations..."
	$(DC) exec $(BACKEND) php artisan migrate --force
	@echo "✅ Migrations completed"

db-fresh:
	@echo "🔄 Running migrations..."
	$(DC) exec $(BACKEND) php artisan migrate:fresh
	@echo "✅ Migrations completed"

# =========================
# Frontend
# =========================
.PHONY: frontend-install frontend frontend-dev

frontend-install:
	@echo "📦 Installing frontend dependencies..."
	@sleep 3  # Give container time to start
	$(DC) exec $(FRONTEND) pnpm install
	$(DC) exec $(FRONTEND) sh -c '[ ! -f .env ] && cp .env.example .env || echo "✅ .env already exists"'
	@echo "✅ Frontend dependencies installed"

frontend:
	$(DC) exec $(FRONTEND) sh

frontend-dev:
	@echo "🚀 Starting Next.js dev server..."
	$(DC) exec $(FRONTEND) pnpm dev

# =========================
# MJML Emails
# =========================
.PHONY: emails

emails:
	@echo "📧 Building MJML emails..."
	$(DC) run --rm mjml sh -c "npm install -g mjml && npm run emails:build"
	$(DC) exec $(BACKEND) php artisan optimize:clear
	@echo "✅ Emails built"

# =========================
# Utilities
# =========================
.PHONY: clean rebuild

clean:
	$(DC) down -v
	rm -rf backend/vendor backend/node_modules
	rm -rf frontend/node_modules frontend/.next

rebuild: clean build up