FROM php:fpm

WORKDIR /var/www

RUN docker-php-ext-install pdo pdo_mysql mysqli mysql

RUN apt-get update \
&& apt-get install -y libmemcached-dev zlib1g-dev

RUN pecl install memcached xdebug \
&& docker-php-ext-enable memcached xdebug
