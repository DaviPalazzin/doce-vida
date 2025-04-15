FROM php:8.2-apache AS final

# Instala mysqli e outros módulos
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite actions

USER www-data

