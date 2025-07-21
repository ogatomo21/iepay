FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev unzip zip git \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd

RUN a2enmod rewrite

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html
