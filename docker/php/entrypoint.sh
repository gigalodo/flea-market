#!/bin/bash
set -e

# パーミッション修正（毎回）
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Laravel キャッシュクリア（起動時に毎回）
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

# Laravel ログを標準出力にリンク
ln -sf /dev/stderr /var/www/storage/logs/laravel.log

# 初回マイグレーション & シーディング
if [ ! -f /var/www/.migrated ]; then
    echo "Running initial migration & seeding..."
    php artisan migrate --force
    php artisan db:seed --force
    touch /var/www/.migrated

    php artisan storage:link
    
fi

# supervisord 起動
exec /usr/bin/supervisord -n
