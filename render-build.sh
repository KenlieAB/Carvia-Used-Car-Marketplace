#!/usr/bin/env bash
# exit on error
set -e

echo "--- Installing PHP Dependencies ---"
composer install --no-dev --optimize-autoloader

echo "--- Installing Node Dependencies & Building Assets ---"
npm install
npm run build

echo "--- Preparing Laravel ---"
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan storage:link || true

echo "--- Optimizing Cache ---"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--- Build Completed Successfully! ---"
