#!/bin/bash
set -e

    echo "Running add permmit..."
# パーミッション修正（毎回）
mkdir -p /var/www/storage/logs
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

    echo "Running cache clear..."
# Laravel キャッシュクリア（起動時に毎回）
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear


    echo "Running cleate link..."
# Laravel ログを標準出力にリンク
ln -sf /dev/stderr /var/www/storage/logs/laravel.log


    echo "migration fase..."
# 初回マイグレーション & シーディング
if ! php artisan migrate:status | grep -q "create_users_table"; then

    echo "Running initial migration & seeding..."
    php artisan migrate --force
    php artisan db:seed --force

fi

php artisan storage:link

# supervisord 起動
exec /usr/bin/supervisord -n
