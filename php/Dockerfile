FROM php:7.4-apache

RUN docker-php-ext-install -j$(nproc) mysqli && docker-php-ext-enable mysqli
RUN a2enmod rewrite
RUN service apache2 restart
RUN apt-get update
