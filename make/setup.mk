# =========================
# Setup
# =========================

.PHONY: setup setup-php setup-node

setup: env build up wait-services setup-php emails setup-node
	@echo "✅ Project ready. Visit http://localhost:8080"

setup-php:
ifeq ($(CI),1)
	$(DC) exec -T $(BACKEND) composer setup
else
	$(DC) exec $(BACKEND) composer setup
endif

setup-node:
ifeq ($(CI),1)
	$(DC) exec -T $(FRONTEND) pnpm install
	$(DC) exec $(FRONTEND) pnpm run build
else
	$(DC) exec $(FRONTEND) pnpm install
	$(DC) exec $(FRONTEND) pnpm run dev
endif

# =========================
# Setup utils
# =========================
.PHONY: env wait-services emails

env:
	@if [ ! -f src/.env ]; then \
		echo "📄 Creating .env from .env.example"; \
		cp backend/.env.example backend/.env; \
		cp frontend/.env.example frontend/.env; \
	else \
		echo "📄 .env already exists"; \
	fi

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

emails:
	@echo "📧 Building MJML emails..."
	$(DC) run --rm mjml sh -c "npm install -g mjml && npm run emails:build"
	$(DC) exec $(BACKEND) php artisan optimize:clear
	@echo "✅ Emails built"