FROM php:8.1-fpm

# nginx インストール
RUN apt update && apt install -y nginx

# デフォルト設定を削除（これ超重要）
RUN rm -f /etc/nginx/sites-enabled/default
RUN rm -f /etc/nginx/conf.d/default.conf

# PHP 設定
COPY docker/php/php.ini /usr/local/etc/php/

RUN apt update \
  && apt install -y libpq-dev zlib1g-dev libzip-dev unzip \
  && docker-php-ext-install pdo_pgsql zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

WORKDIR /var/www

# Laravel ソースコピー
COPY src ./


RUN composer install --no-dev --optimize-autoloader

# パーミッション修正
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache


# キャッシュクリア
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan cache:clear \
    && php artisan view:clear


# nginx config を配置
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Laravel エラーログを標準出力に
RUN ln -sf /dev/stderr /var/www/storage/logs/laravel.log

# supervisor で両方起動
RUN apt install -y supervisor
COPY docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord", "-n"]
