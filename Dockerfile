FROM php:8.2-apache

# Instala dependencias de sistema y extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Habilita mod_rewrite de Apache para Laravel
RUN a2enmod rewrite

# Copia Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia todo el proyecto al contenedor
COPY . .

# Marca el directorio como seguro para Git (soluciona "dubious ownership")
RUN git config --global --add safe.directory /var/www/html

# Instala dependencias de PHP con Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Da permisos a Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Configura Apache (para que use public/ como raíz)
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Expone el puerto 80
EXPOSE 80
