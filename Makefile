.PHONY: dshell

dshell:
	docker-compose up -d nginx
	docker-compose run --service-ports --rm --entrypoint=bash php
setup:
	composer install
	php artisan key:generate
	php artisan config:cache
	php artisan migrate
	php artisan test
	php artisan db:seed
