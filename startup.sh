#!/bin/bash
cp /home/site/wwwroot/nginx-laravel.conf /etc/nginx/sites-available/default
service nginx reload
cd /home/site/wwwroot

# Crear el fichero SQLite si no existe (primer arranque)
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
fi

php artisan config:cache
php artisan route:cache
php artisan migrate --force
php artisan storage:link || true
chmod -R 775 storage bootstrap/cache
chmod 664 database/database.sqlite
