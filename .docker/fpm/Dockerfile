FROM php:8.3.10-fpm

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN apt-get update && apt-get install --no-install-recommends --yes \
        # composer dependencies
		libzip-dev unzip \
    && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-enable opcache
RUN docker-php-ext-install -j$(nproc) \
    zip \
    pdo_mysql
RUN pecl install igbinary-3.2.15 && docker-php-ext-enable igbinary
RUN pecl install --configureoptions 'enable-redis-igbinary="yes"' redis-6.0.2 && docker-php-ext-enable redis

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
USER www-data
WORKDIR /srv/laravel-app
COPY --chown=www-data:www-data composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-scripts
COPY --chown=www-data:www-data . .
RUN composer install --optimize-autoloader --no-dev

USER root
