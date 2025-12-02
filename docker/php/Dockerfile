FROM php:8.1-fpm

# 必要なパッケージ
RUN apt update \
    && apt install -y nginx libpq-dev zlib1g-dev libzip-dev unzip \
    && docker-php-ext-install pdo_pgsql zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www

# アプリコピー
COPY src/ ./

# Laravel install
RUN composer install --no-dev --optimize-autoloader

# Nginx 設定コピー
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Laravel storage を書き込み可能に
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# ポート
EXPOSE 80

# Nginx フォアグラウンド起動
CMD ["nginx", "-g", "daemon off;"]
