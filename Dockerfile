FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y libssl-dev git unzip

# Instalar extensión de MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Copiar los archivos del proyecto
COPY . /var/www/html/

# Instalar Composer correctamente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

EXPOSE 80