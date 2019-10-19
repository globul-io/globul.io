.PHONY: install update assets webpack reset up down ps clean warm init

help:
	@echo "Please use \`make <target>' where <target> is one of"
	@echo #
	@echo "  install    to install composer and bower dependencies"
	@echo "  update     to update composer and bower dependencies"
	@echo "  assets     to install symfony and npm assets"
	@echo "  webpack    to build webpack assets"
	@echo "  reset      to recreate the docker environment"
	@echo "  up         to start docker container"
	@echo "  down       to stop docker container"
	@echo "  ps         to show docker container status"
	@echo "  clean      to clean the cache"
	@echo "  warm       to clean and warmup the cache"
	@echo "  init       to initialize the site"

console=docker-compose exec php bin/console

export PROJECT_NAME := $(shell grep PROJECT_NAME .env | awk -F '=' '{print $$NF}')

###################
# Build
###################

install: vendor yarn.lock assets

vendor: composer.json composer.lock
	@composer install --prefer-dist

update: update-composer update-assets

update-composer:
	@composer update --prefer-dist

update-assets: yarn.lock
	@yarn upgrade --force

###################
# Assets
###################

assets: webpack symfony-assets

webpack: yarn.lock
	@yarn dev

webpack-prod:
	@yarn build

yarn.lock: node_modules
	@yarn install

node_modules:
	@mkdir -p $@

symfony-assets:
	@$(console) --env=prod ckeditor:install --clear=skip
	@$(console) --env=prod assets:install public

###################
# Quality
###################

qs: cs lint normalize permissions test phpstan validate

cs:
	vendor-bin/csfixer/vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix -v

cs-clean:
	find ./src -iname '.php_cs.cache*' -exec rm -r "{}" \;

csf: cs-clean cs

lint:
	find ./src -name '*.yml' -name '*.yaml' -not -path '*/vendor/*' | xargs yaml-lint
	find . \( -name '*.xml' -or -name '*.xml.dist' -or -name '*.xlf' \) -not -path '*/vendor/*' -not -path '*/node_modules/*' -not -path '*/.*' -type f -exec xmllint --encode UTF-8 --output '{}' --format '{}' \;

validate: warm
	console lint:yaml config
	console lint:twig templates
	console lint:xliff translations
	console doctrine:schema:validate --skip-sync
	console doctrine:schema:update --dump-sql

normalize:
	composer normalize

permissions:
	find ./src -name '*.php' | xargs chmod a-x

test:
	docker-compose exec php bin/phpunit

coverage:
	docker-compose exec php bin/phpunit --coverage-clover build/coverage.xml

phpstan:
	docker-compose exec php bin/phpstan analyse -c phpstan.neon -l 7 src tests

###################
# Docker
###################

start: docker-up sync-start

stop: sync-stop docker-down

reset: sync-stop stop
	@rm -rf var/cache/* var/log/*
	@docker-compose rm -f -v

sync-start:
	@if [[ ! $$(mutagen list) =~ '/var/www/symfony' ]]; then \
		mutagen create \
						-m two-way-resolved \
					   	--default-owner-beta=www-data \
					   	`pwd` docker://${PROJECT_NAME}-nginx/var/www/symfony ; \
	fi
	@mutagen resume /var/www/symfony
	@echo "Waiting for synchronization to complete"
	@while [[ ! $$(mutagen list /var/www/symfony) =~ 'Status: Watching for changes' ]]; do \
		printf "."; \
		sleep 10; \
	done
	@echo "done"

sync-stop:
	@mutagen terminate --all

docker-up:
	@docker-compose up -d

docker-down:
	@docker-compose down

ps:
	@docker-compose ps
	@mutagen list

###################
# Application
###################

clean:
	@$(console) cache:clear

warm: clean
	@$(console) cache:warmup

init: init-database init-routing init-context

init-database:
	@$(console) doctrine:schema:update --dump-sql -f

init-routing:
	@$(console) sonata:page:update-core-routes --site=all --clean
	@$(console) sonata:page:create-snapshots --site=all

init-context:
	@$(console) sonata:media:fix-media-context
	@$(console) sonata:classification:fix-context

###################
# Catch defaults
###################

%:
	@:

.DEFAULT :
	@:
