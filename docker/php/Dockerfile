FROM php:5.6-apache

RUN docker-php-ext-install mysql mysqli pdo pdo_mysql

RUN a2enmod rewrite
RUN echo "date.timezone = Asia/Tokyo" > /usr/local/etc/php/conf.d/timezone.ini
COPY apache.conf /etc/apache2/sites-available/000-default.conf