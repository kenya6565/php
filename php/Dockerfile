FROM php:fpm

RUN apt-get update && apt-get install -y vim
RUN docker-php-ext-install mysqli pdo_mysql
COPY php.ini ~/etc/