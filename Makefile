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
	@echo "  make setup                → Full project setup"
	@echo "  make build                → Build docker images"
	@echo "  make up                   → Start containers in background"
	@echo "  make down                 → Stop containers"
	@echo "  make restart              → Restart containers"
	@echo "  make logs                 → Show all logs"
	@echo ""
	@echo "  make back-install         → Install backend dependencies (composer, .env, key)"
	@echo "  make back-console         → Shell inside backend container"
	@echo "  make back-lint            → Run composer lint in backend"
	@echo "  make back-refactor        → Run composer refactor in backend"
	@echo "  make back-check-types     → Run composer check:types in backend"
	@echo "  make back-check           → Run composer check in backend"
	@echo "  make migrate              → Run Laravel migrations"
	@echo "  make db-refresh           → Run fresh Laravel migrations"
	@echo ""
	@echo "  make front-install        → Install frontend dependencies (pnpm, .env)"
	@echo "  make front-dev            → Start Next.js dev server"
	@echo "  make front-console        → Shell inside front container"
	@echo "  make front-lint           → Run lint script in frontend"
	@echo "  make front-format         → Run format script in frontend"
	@echo "  make front-check          → Run check script in frontend"
	@echo ""
	@echo "  make emails               → Build MJML emails"
	@echo ""
	@echo "  make check                → Run checks for both backend and frontend"
	@echo "  make clean                → Remove containers and node_modules/vendor"
	@echo "  make rebuild              → Clean + build + up"
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
setup: build up wait-services back-install migrate emails front-install front-dev
	@echo ""
	@echo "✅ Project is ready!"
	@echo "   Backend:  http://localhost:8080"
	@echo "   Frontend: http://localhost:3000"
	@echo ""
	@echo "Start dev server: make front-dev"
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
.PHONY: back-install back-console back-lint back-refactor back-check-types back-check migrate

back-install:
	@echo "📦 Installing backend dependencies..."
	$(DC) exec $(BACKEND) composer setup
	@echo "✅ Backend dependencies installed"

back-console:
	$(DC) exec $(BACKEND) bash

back-lint:
	@echo "🔄 Running composer lint..."
	$(DC) exec $(BACKEND) composer lint
	@echo "✅ Linting completed"

back-refactor:
	@echo "🔄 Running composer refactor..."
	$(DC) exec $(BACKEND) composer refactor
	@echo "✅ Refactor completed"

back-check-types:
	@echo "🔄 Running composer test:types..."
	$(DC) exec $(BACKEND) composer test:types
	@echo "✅ Types check completed"

back-check:
	@echo "🔄 Running composer check..."
	$(DC) exec $(BACKEND) composer check
	@echo "✅ Checks completed"

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
.PHONY: front-install front-dev front-console front-lint front-format front-check

front-install:
	@echo "📦 Installing frontend dependencies..."
	@sleep 3  # Give container time to start
	$(DC) exec $(FRONTEND) pnpm install
	$(DC) exec $(FRONTEND) sh -c '[ ! -f .env ] && cp .env.example .env || echo "✅ .env already exists"'
	@echo "✅ Frontend dependencies installed"

front-dev:
	@echo "🚀 Starting Next.js dev server..."
	@echo ""
	@echo "⏳ First page load will take ~10s to compile"
	@echo "   Please be patient, this is normal for Next.js"
	@echo ""
	$(DC) exec $(FRONTEND) pnpm dev

front-console:
	$(DC) exec $(FRONTEND) sh

front-lint:
	@echo "🔄 Running pnpm lint..."
	$(DC) exec $(FRONTEND) pnpm lint
	@echo "✅ Linting completed"

front-format:
	@echo "🔄 Running pnpm format..."
	$(DC) exec $(FRONTEND) pnpm format
	@echo "✅ Formating completed"

front-check:
	@echo "🔄 Running pnpm check..."
	$(DC) exec $(FRONTEND) pnpm check
	@echo "✅ Checks completed"

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
.PHONY: check clean rebuild

check: back-check front-check

clean:
	$(DC) down -v
	rm -rf backend/vendor backend/node_modules
	rm -rf frontend/node_modules frontend/.next

rebuild: clean setup