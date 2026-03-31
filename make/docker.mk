# =========================
# Variables
# =========================
SERVICES ?= $(filter-out $@,$(MAKECMDGOALS))

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
	$(DC) restart $(SERVICES)

logs:
	$(DC) logs -f $(SERVICES)