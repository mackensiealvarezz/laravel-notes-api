.PHONY: dshell

dshell:
	docker-compose up -d nginx
	docker-compose run --service-ports --rm --entrypoint=bash php
setup:
	php artisan key:generate
	php artisan migrate
	php artisan test
	php artisan db:seed
