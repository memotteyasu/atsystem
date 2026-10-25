FROM php:5.6-apache

RUN echo "deb [trusted=yes] http://archive.debian.org/debian jessie main" > /etc/apt/sources.list \
    && echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid-until

RUN apt-get update \
    && apt-get install -y --allow-unauthenticated --allow-downgrades \
        zlib1g=1:1.2.8.dfsg-2+b1 \
        zlib1g-dev=1:1.2.8.dfsg-2+b1 \
        libpng12-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
    && docker-php-ext-configure gd \
        --with-png-dir=/usr \
        --with-jpeg-dir=/usr \
        --with-freetype-dir=/usr \
    && docker-php-ext-install gd

RUN docker-php-ext-install mysql mysqli pdo pdo_mysql

RUN a2enmod rewrite
RUN echo "date.timezone = Asia/Tokyo" > /usr/local/etc/php/conf.d/timezone.ini

COPY apache.conf /etc/apache2/sites-available/000-default.conf