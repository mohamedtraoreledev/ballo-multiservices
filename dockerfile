FROM php:8.2-apache

# Installer PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copier le projet
COPY . /var/www/html/

# Activer rewrite
RUN a2enmod rewrite

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80