FROM php:8-fpm AS base

RUN apt update && apt install -y zlib1g-dev libpng-dev git zip

RUN docker-php-ext-install exif gd pdo_mysql

FROM base AS dev

# Install node
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -

RUN apt update && apt install -y vim nodejs

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html
RUN composer install --no-dev --no-scripts
RUN composer dump-autoload -o
