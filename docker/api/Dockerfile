FROM php:fpm-alpine
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apk add shadow && usermod -u 1000 www-data && groupmod -g 1000 www-data

COPY backend/ /usr/share/nginx/html
RUN chown -R www-data:www-data /usr/share/nginx/html

CMD ["php-fpm"]