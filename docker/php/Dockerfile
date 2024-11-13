FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    unzip git libzip-dev && \
    docker-php-ext-configure zip && \
    docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN composer create-project --prefer-dist laravel/lumen ./ && \
    chown -R www-data:www-data /var/www/html

EXPOSE 9000
