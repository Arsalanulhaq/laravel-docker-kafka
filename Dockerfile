# Global settings
ARG PHP_VERSION=8.1
ARG NGINX_VERSION=1.17
#ARG ALPINE_VERSION=3.13

# FROM php:${PHP_VERSION}-fpm-alpine AS products_php
FROM thecodingmachine/php:8.1-v4-fpm AS products_php

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

# build for production
ARG APP_ENV=production

WORKDIR /var/www/application

# copy everything, excluding the one from .dockerignore file
COPY . ./
#RUN set -eux; \
RUN mkdir -p storage/logs storage/framework bootstrap/cache; \
    composer install --prefer-dist --no-progress --no-suggest --optimize-autoloader; \
    composer clear-cache

EXPOSE 8080

# NGINX
# FROM nginx:${NGINX_VERSION}-alpine AS products_nginx

# WORKDIR /var/www/application

# COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/
# COPY --from=products_php /var/www/application/public public/
