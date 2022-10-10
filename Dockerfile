# Global settings
ARG PHP_VERSION=8.1
ARG NGINX_VERSION=1.17
#ARG ALPINE_VERSION=3.13

#FROM php:${PHP_VERSION}-fpm-alpine${ALPINE_VERSION} AS products_php
FROM php:${PHP_VERSION}-fpm-alpine AS products_php

# persistent / runtime deps
RUN apk add --no-cache acl file gettext git;
# Install & clean up dependencies
RUN apk --no-cache --update --repository http://dl-cdn.alpinelinux.org/alpine/v3.16/main/ add \
    curl \
    openssl \
    openssl-dev \
    libtool \
    icu \
    icu-libs \
    icu-dev \
    libxml2-dev \
#    librdkafka-dev \
#    autoconf \
&& apk --no-cache --update --repository http://dl-3.alpinelinux.org/alpine/v3.16/community/ add \
    php8-sockets \
    php8-zlib \
    php8-intl \
    php8-opcache \
    php8-bcmath \
    php8-soap \
#    php8-pear \
&& docker-php-ext-install \
    pdo_mysql \
    sockets \
    intl \
    opcache \
    bcmath \
    soap \
#&&  pecl install rdkafka \
&& rm -rf /var/cache/apk/* /tmp/*
#&& pecl install rdkafka

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY docker/php-fpm/php.ini /usr/local/etc/php/php.ini
COPY docker/php-fpm/php-cli.ini /usr/local/etc/php/php-cli.ini
COPY docker/php-fpm/zz-docker.conf /usr/local/etc/php-fpm.d/zzz-docker.conf

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /var/www/application

# build for production
ARG APP_ENV=production

# copy everything, excluding the one from .dockerignore file
COPY . ./

#RUN set -eux; \
RUN mkdir -p storage/logs storage/framework bootstrap/cache; \
    composer install --prefer-dist --no-progress --no-suggest --optimize-autoloader; \
    composer clear-cache

COPY docker/php-fpm/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]

# NGINX
FROM nginx:${NGINX_VERSION}-alpine AS products_nginx

COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/

WORKDIR /var/www/application

COPY --from=products_php /var/www/application/public public/
