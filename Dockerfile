FROM php:8.1.6-fpm-alpine3.15 AS products_php

# Get installer script
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
# Install php exts
RUN install-php-extensions gd pdo_mysql zip redis rdkafka pcntl
# Install deps & composer
RUN apk add --no-cache \ 
    unzip \ 
    shadow \ 
    nodejs \
    npm \
    nano && curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php && php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Create user & group so docker doesn't run as root
WORKDIR /var/www/html
RUN groupadd -g 1000 dev && useradd -u 1000 -g dev arsalan
# Change ownership to new user
COPY --chown=arsalan:dev . /var/www/html
# Run as new user
USER arsalan

# RUN composer install && composer require ensi/laravel-phprdkafka
# RUN php artisan vendor:publish --provider="Ensi\LaravelPhpRdKafka\LaravelPhpRdKafkaServiceProvider" --tag="kafka-config"