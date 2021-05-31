SHELL = /bin/sh

current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
container_cli=docker run -e "TERM=xterm-256color" --tty=true --rm -v $(current-dir):/code:cached pheature/pheature-php-dev:latest

.PHONY: help
help: ## Display available targets
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {sub("\\\\n",sprintf("\n%22c"," "), $$2);printf " \033[36m%-20s\033[0m  %s\n", $$1, $$2}' $(MAKEFILE_LIST)

check-all: ## Run all checks
	$(container_cli) composer check-all

.PHONY: unit
unit: ## Run unit tests
	$(container_cli) composer test

.PHONY: static
static: ## Run psalm & phpstan static analysis
	$(container_cli) /bin/sh -c "composer psalm && composer inspect"

.PHONY: cs
cs: ## Run PHP Coding Standard (CS) check and PHP Coding Standard (CS) fixer
	$(container_cli) /bin/sh -c "composer cs-check && composer cs-fix"

.PHONY: infection
infection: ## Run infection to detect mutants in unit tests
	$(container_cli) composer infection
