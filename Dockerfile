FROM php:8.2-apache

# Instalar dependencias del sistema requeridas
RUN apt-get update && apt-get install -y libssl-dev git unzip libzstd-dev && rm -rf /var/lib/apt/lists/*

# Instalar la extensión de MongoDB para PHP
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Copiar los archivos del proyecto al servidor Apache
COPY . /var/www/html/

# Instalar las librerías de PHP usando Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

EXPOSE 80