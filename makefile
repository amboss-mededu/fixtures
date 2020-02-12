.DEFAULT_GOAL := help

.PHONY: build
build: ## Rebuilding container for test
	docker-compose build

.PHONY: install
install: ## Installing dependencies
	docker-compose run fixtures_tests composer install

.PHONY: test
test: ## Running tests
	docker-compose run fixtures_tests ./run_tests.sh
	docker-compose down -v

.PHONY: help
help: ## Help
	@grep -E '^[0-9a-zA-Z_/()$$-]+:.*?## .*$$' $(lastword $(MAKEFILE_LIST)) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
