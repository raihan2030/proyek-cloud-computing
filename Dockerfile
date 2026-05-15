# Menggunakan image PHP-FPM versi 8.5
FROM php:8.5-fpm

# Instalasi system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev

# Bersihkan cache apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalasi ekstensi PHP untuk Laravel dan MySQL
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Dapatkan Composer terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html