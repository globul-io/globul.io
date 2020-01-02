.PHONY: help
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

.PHONY: install
install: vendor yarn.lock assets

vendor: composer.json composer.lock
	composer validate --strict
	composer install
	composer normalize

.PHONY: update
update: vendor
	composer update

update-assets: yarn.lock
	yarn upgrade --force

###################
# Assets
###################

.PHONY: assets
assets: webpack symfony-assets

.PHONY: webpack
webpack: yarn.lock
	yarn dev

.PHONY: prod
prod:
	yarn build

yarn.lock: node_modules
	yarn install

node_modules:
	mkdir -p $@

.PHONY: symfony-assets
symfony-assets: vendor
	$(console) --env=prod ckeditor:install --clear=skip
	$(console) --env=prod assets:install public

###################
# Quality
###################

.PHONY: qs
qs: cs lint permissions test phpstan validate

.PHONY: cs
cs: vendor
	vendor/bin/php-cs-fixer fix -v

.PHONY: cs-clean
cs-clean:
	find ./src -iname '.php_cs.cache*' -exec rm -r "{}" \;

.PHONY: csf
csf: cs-clean cs

.PHONY: lint
lint:
	find ./src -name '*.yml' -name '*.yaml' -not -path '*/vendor/*' | xargs yaml-lint
	find . \( -name '*.xml' -or -name '*.xml.dist' -or -name '*.xlf' \) -not -path '*/vendor/*' -not -path '*/node_modules/*' -not -path '*/.*' -type f -exec xmllint --encode UTF-8 --output '{}' --format '{}' \;
	console lint:yaml config
	console lint:twig templates
	console lint:xliff translations
	console lint:container

.PHONY: validate
validate: warm
	console doctrine:schema:validate --skip-sync
	console doctrine:schema:update --dump-sql

.PHONY: permissions
permissions:
	find ./src -name '*.php' | xargs chmod a-x

.PHONY: test
test: vendor
	vendor/bin/phpunit

.PHONY: coverage
coverage: vendor
	vendor/bin/phpunit --coverage-clover build/coverage.xml

.PHONY: phpstan
phpstan: vendor
	vendor/bin/phpstan analyse -c phpstan.neon -l 7 src tests

.PHONY: phpstan-baseline
phpstan-baseline: vendor
	mkdir -p .build/phpstan
	echo '' > phpstan-baseline.neon
	vendor/bin/phpstan analyze --configuration=phpstan.neon --error-format=baselineNeon > phpstan-baseline.neon || true

###################
# Docker
###################

.PHONY: start
start: docker-up sync-start sync

.PHONY: stop
stop: sync-stop docker-down

.PHONY: reset
reset: sync-destory stop
	rm -rf var/cache/* var/log/*
	docker-compose rm -f -v

.PHONY: sync-start
sync-start:
	@if [[ ! $$(mutagen sync list) =~ 'application' ]]; then \
		mutagen sync create \
		                -n application \
						-i ./var/db \
						-m two-way-resolved \
						--default-directory-mode=0755 \
					   	--default-file-mode=0644 \
					   	--default-owner-beta=www-data \
					   	`pwd` docker://${PROJECT_NAME}-nginx/var/www/symfony ; \
	fi

.PHONY: sync
sync:
	mutagen sync resume application
	@echo "Waiting for synchronization to complete"
	@while [[ ! $$(mutagen sync list application) =~ 'Status: Watching for changes' ]]; do \
		printf "."; \
		sleep 10; \
	done
	@echo "done"

.PHONY: sync-stop
sync-stop:
	mutagen sync terminate --all

.PHONY: docker-up
docker-up:
	docker-compose up -d

.PHONY: docker-down
docker-down:
	docker-compose down

.PHONY: ps
ps:
	docker-compose ps
	mutagen list

###################
# Application
###################

.PHONY: clean
clean:
	$(console) cache:clear

.PHONY: warm
warm: clean
	$(console) cache:warmup

.PHONY: init
init: init-database init-site init-routing init-context

.PHONY: init-database
init-database:
	$(console) doctrine:schema:update --dump-sql -f

.PHONY: init-site
init-site:
	$(console) sonata:page:create-site --enabled=true --name=localhost --locale=- --host=localhost --relativePath=/ --enabledFrom=now --enabledTo="+10 years" --default=true -n

.PHONY: init-routing
init-routing:
	$(console) sonata:page:update-core-routes --site=all --clean
	$(console) sonata:page:create-snapshots --site=all

.PHONY: init-context
init-context:
	$(console) sonata:media:fix-media-context
	$(console) sonata:classification:fix-context
