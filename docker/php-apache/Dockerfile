# Use an official PHP runtime
FROM php:8.2-apache

# Enable Apache modules
RUN a2enmod rewrite

# Install any extensions you need
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Change Document Root
ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN service apache2 restart