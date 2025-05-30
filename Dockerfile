# Dockerfile
FROM php:8.2-apache

# Habilita extensiones necesarias
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copia el código fuente a la carpeta del servidor web
COPY . /var/www/html/

# Da permisos (si es necesario)
RUN chown -R www-data:www-data /var/www/html



