THIS_FILE := $(lastword $(MAKEFILE_LIST))

.PHONY: help install up down migrate tests psr wait-db bash-php

help: ## Display available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(THIS_FILE) | \
	awk 'BEGIN {FS = ":.*?## "}; {printf "\033[33m%-20s\033[0m %s\n", $$1, $$2}'

install: ## Install and prepare project
	@if [ ! -f src/.env ]; then \
		echo "Creating .env from .env.example"; \
		cp src/.env.example src/.env; \
	fi

	make up
	make wait-db

	docker compose run --rm composer composer install

	docker compose run --rm php php artisan key:generate
	docker compose run --rm php php artisan migrate --force

	make tests
	make psr

up: ## Start containers
	docker compose up -d

down: ## Stop containers
	docker compose down

migrate: ## Run migrations
	docker compose run --rm php php artisan migrate --force

tests: ## Run tests
	docker compose run --rm php ./vendor/bin/phpunit --testdox

psr: ## Run Laravel Pint
	docker compose run --rm php ./vendor/bin/pint app --test

bash-php: ## Open bash inside php container
	docker compose run --rm php bash

wait-db: ## Wait until MySQL is ready
	@echo "Waiting for database..."
	@until docker compose exec db mysqladmin ping -h"127.0.0.1" --silent; do \
		sleep 1; \
	done
	@echo "Database is ready"