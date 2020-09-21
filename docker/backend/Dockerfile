FROM php:7.4-apache
WORKDIR /var/www/html

RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY backend/ /var/www/html/
COPY docker/backend/start.sh /usr/local/bin/start
RUN chown -R www-data:www-data /var/www/html \
    && chmod +x /usr/local/bin/start

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite headers

CMD ["/usr/local/bin/start"]