# =========================
# Variables
# =========================
DC = docker compose
BACKEND = backend
FRONTEND = frontend
ARGS = $(filter-out $@,$(MAKECMDGOALS))
CI ?=0

# =========================
# Includes
# =========================
include make/docker.mk
include make/setup.mk
include make/utils.mk

# =========================
# Commands
# =========================

.PHONY: bash sh artisan composer pnpm

bash:
	$(DC) exec $(BACKEND) bash

sh:
	$(DC) exec $(FRONTEND) sh

artisan:
	$(DC) exec $(BACKEND) php artisan $(ARGS)

composer:
	$(DC) exec $(BACKEND) composer $(ARGS)

pnpm:
	$(DC) exec $(FRONTEND) pnpm $(ARGS)

# =========================
# Catch-all
# =========================

%:
	@: