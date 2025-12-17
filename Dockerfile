PHP 8.2 con FPM (FastCGI Process Manager)
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

# Instalar Redis para PHP
RUN pecl install redis \
    && docker-php-ext-enable redis

# Instalar Composer (gestor de dependencias de PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . /var/www

# Instalar dependencias de Laravel
RUN composer install --optimize-autoloader --no-dev

# Dar permisos a carpetas necesarias
RUN chown -R www-data:www-data \
    /var/www/storage \
    /var/www/bootstrap/cache

# Exponer puerto 9000 para PHP-FPM
EXPOSE 9000

# Comando para iniciar PHP-FPM
CMD ["php-fpm"]