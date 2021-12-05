FROM php:7.4-fpm

RUN docker-php-ext-install pdo_mysql
RUN apt update && apt install libzip-dev -y && docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY . .


CMD bash -c "composer install && php-fpm"