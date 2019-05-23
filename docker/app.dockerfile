FROM php:7.3-fpm

RUN apt-get update && apt-get install -y \
    mysql-client libmagickwand-dev --no-install-recommends \
    build-essential \
    redis-tools \
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
    openssh-server

RUN docker-php-ext-install pdo_mysql mysqli
