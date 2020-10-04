FROM composer as composer
WORKDIR /app
COPY backend/ /app
RUN composer install

FROM php:7.4-fpm-alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY backend/ /var/www/html/api
COPY --from=composer /app/vendor /var/www/html/api/vendor
RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html
EXPOSE 9000
