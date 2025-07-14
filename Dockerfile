FROM php:8.3-fpm AS builder

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install --no-interaction --no-scripts --no-dev --optimize-autoloader

COPY . .

RUN php artisan route:cache && \
    php artisan view:cache

RUN chown -R www-data:www-data storage bootstrap/cache

FROM php:8.3-fpm-alpine

WORKDIR /var/www

RUN apk add --no-cache --update \
    $PHPIZE_DEPS \
    libzip-dev \
    oniguruma-dev \
    libexif-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip \
    && apk del $PHPIZE_DEPS

COPY --from=builder /var/www .

EXPOSE 9000

CMD ["php-fpm"]
