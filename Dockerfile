# Usar la imagen oficial de PHP con servidor Apache
FROM php:8.2-apache

# Instalar dependencias del sistema para MongoDB y Composer
RUN apt-get update && apt-get install -y libssl-dev git unzip

# Compilar e instalar la extensión nativa de MongoDB para PHP
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Copiar nuestro código al directorio público de Apache
COPY . /var/www/html/

# Copiar Composer desde su imagen oficial e instalar librerías
COPY --from-composer:latest /usr/bin/composer /usr/bin/composer
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

# Exponer el puerto web
EXPOSE 80