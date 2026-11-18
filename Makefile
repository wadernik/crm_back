# Docker control targets. Run from the repository root.
#
# The Compose files live in docker/, so every command needs an explicit
# --env-file: Compose would otherwise look for docker/.env instead of the
# repository root env file (see docker/README.md).

COMPOSE      := docker compose --env-file .env        -f docker/compose.yaml
COMPOSE_TEST := docker compose --env-file .env.testing -f docker/compose.test.yaml

.DEFAULT_GOAL := help

.PHONY: help up up-queue down down-v test-down

help:
	@echo "make up        - start the development stack (postgres, redis, rabbitmq, app, nginx)"
	@echo "make up-queue  - same, plus the optional queue worker (profile: queue)"
	@echo "make down      - stop and remove the dev containers, keep data volumes"
	@echo "make down-v    - same, plus remove the data volumes"
	@echo "make test-down - tear down the isolated test stack (crm-test)"

up:
	$(COMPOSE) up -d

up-queue:
	$(COMPOSE) --profile queue up -d

down:
	$(COMPOSE) down

down-v:
	$(COMPOSE) down -v

test-down:
	$(COMPOSE_TEST) down -v
