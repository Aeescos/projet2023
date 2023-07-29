.PHONY: deploy install

# Chemins du projet
PROJECT_PATH := /home/xoro8042/api_auth/fylap.com

# Commandes Symfony
CONSOLE := php bin/console
COMPOSER := composer

deploy:
	@ssh o2switch 'cd $(PROJECT_PATH) && git pull origin master && make install'

install: vendor/autoload.php .env
	@$(CONSOLE) cache:clear
	@$(CONSOLE) doctrine:migrations:migrate

.env:
	cp .env.example .env
	@$(CONSOLE) key:generate
	@$(CONSOLE) lexik:jwt:generate-keypair

vendor/autoload.php: composer.lock
	@$(COMPOSER) install --ignore-platform-reqs
	@touch vendor/autoload.php
