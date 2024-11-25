FROM php:8.3-fpm

ARG user=www-data
ARG uid=1000

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

WORKDIR /var/www

COPY php/local.ini /usr/local/etc/php/conf.d/local.ini

RUN chown -R www-data:www-data /var/www

EXPOSE 9000

CMD ["php-fpm"]
