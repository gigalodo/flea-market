FROM php:8.1-fpm

COPY docker/php/php.ini /usr/local/etc/php/

RUN apt update \
    && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update

WORKDIR /var/www

COPY src .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:cache \
    && php artisan route:cache

CMD php artisan serve --host=0.0.0.0 --port=${PORT}
