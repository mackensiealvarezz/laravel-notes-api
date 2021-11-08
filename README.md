# Laravel Notes Api
This application comes with a CRUD api for notes.

# Setup

## First clone the repo
    git clone https://github.com/mackensiealvarezz/laravel-notes-api

## Copy the .env.example
Copy the **.env.example** and rename it to **.env**

## Run docker
I have included a Makefile to make the development environment easier to setup. You just need to run

    make dshell

This command willl build the docker image and take you directly to the container shell after.

After it takes you to the shell, run

    make setup

This command will auto run migrations, test and seed fake data.

# If "make" command isn't working
If for some reason make command isn't working you can run:

    docker-compose up -d nginx
	docker-compose run --service-ports --rm --entrypoint=bash php

After it takes you to the shell run

    php artisan key:generate
	php artisan migrate
	php artisan test
	php artisan db:seed

# Users

By default I have included 3 users
- user1@test.com
- user2@test.com
- user3@test.com

The password for all of them is **password**.

# Curl commands


## Login

    curl --location --request POST 'localhost:8000/api/login' \
    --header 'Accept: application/json' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "email" : "user1@test.com",
        "password": "password"
    }'

## List Notes

    curl --location --request GET 'localhost:8000/api/notes' \
    --header 'Accept: application/json' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer TOKEN'

## Show Note

    curl --location --request GET 'localhost:8000/api/notes/1' \
    --header 'Accept: application/json' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer TOKEN'

## Update Note

    curl --location --request PUT 'localhost:8000/api/notes/1' \
    --header 'Accept: application/json' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer TOKEN' \
    --data-raw '{
        "title" : "new title",
        "note": "new note"
    }'

## Create Note

    curl --location --request POST 'localhost:8000/api/notes' \
    --header 'Accept: application/json' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer TOKEN' \
    --data-raw '{
        "title" : "new title",
        "note": "new note"
    }'

## Delete Note

    curl --location --request DELETE 'localhost:8000/api/notes/1' \
    --header 'Accept: application/json' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer TOKEN'
