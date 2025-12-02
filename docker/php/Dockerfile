FROM php:8.1-fpm

COPY docker/php/php.ini /usr/local/etc/php/

RUN apt update \
  && apt install -y libpq-dev zlib1g-dev libzip-dev unzip \
  && docker-php-ext-install pdo_pgsql zip

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

WORKDIR /var/www

COPY src ./
RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm"]
