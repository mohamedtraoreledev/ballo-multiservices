FROM php:8.2-apache

# Copier le projet
COPY . /var/www/html/

# Activer rewrite (optionnel mais utile)
RUN a2enmod rewrite

# Donner permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80