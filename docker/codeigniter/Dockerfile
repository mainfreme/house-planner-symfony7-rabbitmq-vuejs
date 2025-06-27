FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    libicu-dev libzip-dev zip unzip git curl libonig-dev \
    libssl-dev libcurl4-openssl-dev pkg-config \
    libmagickwand-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip

# MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY ./microservice-ci4 /var/www/html
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
