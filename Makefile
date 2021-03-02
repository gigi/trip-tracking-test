compose=docker-compose

.PHONY:help
help:
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: start
start: build install migrations countries-sync up ## Create dependencies and up local web server

.PHONY: build
build: ## Build docker images
		$(compose) build

.PHONY: install
install: ## Install all dependencies and database structure
		$(compose) run --rm php bash -lc 'COMPOSER_MEMORY_LIMIT=-1 composer install'

.PHONY: migrations
migrations: ## Install all dependencies and database structure
		$(compose) run --rm php bash -lc 'bin/console doctrine:migrations:migrate --no-interaction'

.PHONY: up
up: ## Up containers
		$(compose) up

.PHONY: down
down: ## Downs containers
		$(compose) down

.PHONY: bash
bash: ## Jump into running php container
		$(compose) exec php bash

.PHONY: psalm
psalm: ## Run psalm
		$(compose) run --rm php bash -lc './vendor/bin/psalm'

.PHONY: phpstan
phpstan: ## Run phpstan
		$(compose) run --rm php bash -lc './vendor/bin/phpstan analyze'

.PHONY: cs
cs: ## Run codestyle check
		$(compose) run --rm php bash -lc './vendor/bin/ecs check'

.PHONY: cs-fix
cs-fix: ## Fix codestyle
		$(compose) run --rm php bash -lc './vendor/bin/ecs check src --fix'

.PHONY: qa
qa: cs phpstan psalm ## Run code quality checkers

.PHONY: tests
tests: ## Run tests
		$(compose) run --rm php bash -lc 'bin/phpunit'

.PHONY: coverage
coverage: ## Run tests with inline coverage info
		$(compose) run --rm php bash -lc 'XDEBUG_MODE=coverage php bin/phpunit --coverage-text'

.PHONY: coverage-report
coverage-report: ## Run tests with coverage and save html report to var/coverage
		$(compose) run --rm php bash -lc 'XDEBUG_MODE=coverage php bin/phpunit --coverage-html var/coverage'

.PHONY: countries-sync
countries-sync: ## Syncs asia and europe
		$(compose) run --rm php bash -lc 'bin/console countries:sync asia europe'