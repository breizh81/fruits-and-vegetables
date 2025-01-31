# .PHONY ensures these are treated as commands, not files
.PHONY: up down run test server install console cache-clear assets-install sh

# Variables
DOCKER_COMPOSE = docker-compose
DOCKER_COMPOSE_UP = $(DOCKER_COMPOSE) up -d
DOCKER_COMPOSE_DOWN = $(DOCKER_COMPOSE) down
DOCKER_COMPOSE_RUN = $(DOCKER_COMPOSE) run --rm app
DOCKER_COMPOSE_EXEC = $(DOCKER_COMPOSE) exec app

CONTAINER_NAME = fruits_and_vegetables_app_container
DOCKER_CONTAINER_ID := $(shell docker ps -qf "name=^/$(CONTAINER_NAME)$$")

DOCKER=docker exec -ti $(DOCKER_CONTAINER_ID)
DOCKER_ROOT := docker exec -ti --user root $(DOCKER_CONTAINER_ID)

PHP_CSFIXER_BIN = vendor/bin/php-cs-fixer
PHPCS_BIN = vendor/bin/phpcs
PHPCS_CODESNIFFERFIX_BIN = vendor/bin/phpcbf

build:
	$(DOCKER_COMPOSE) build --no-cache

# Run the containers (start them in detached mode)
up:
	$(DOCKER_COMPOSE) up -d

# Stop and remove the containers
down:
	$(DOCKER_COMPOSE_DOWN)

# Install PHP dependencies
install-composer:
	$(DOCKER_COMPOSE_RUN) composer install

clear-redis:
	docker exec -it fruits_and_vegetables_redis_container redis-cli FLUSHALL

# Run the Symfony tests (PHPUnit)
test:
	$(DOCKER_COMPOSE_RUN) bin/phpunit

# Clear Symfony cache
cache-clear:
	$(DOCKER_COMPOSE_EXEC) php bin/console cache:clear

# Access the app container's shell
sh:
	$(DOCKER_COMPOSE_EXEC) sh

phpcs-fix-dry-run:
	$(DOCKER_ROOT) $(PHP_CSFIXER_BIN) fix --dry-run

phpcs-fix:
	$(DOCKER_ROOT) $(PHP_CSFIXER_BIN) fix

codesniffer-check:
	$(DOCKER_ROOT) $(PHPCS_BIN) --standard=phpcs.xml.dist

codesniffer-fix:
	$(DOCKER_ROOT) $(PHPCS_CODESNIFFERFIX_BIN) --standard=phpcs.xml.dist
