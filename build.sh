#!/bin/bash
set -e

echo "Starting Railway build process..."

# Install PHP extensions if not already installed
echo "Checking PHP extensions..."
php -m | grep -q pdo_mysql || {
    echo "PDO MySQL extension not found. This should be installed by Railway Railpack."
    echo "If you see this message, please check Railway's PHP extension installation."
}

# Run composer install
echo "Running composer install..."
composer install --no-dev --optimize-autoloader --no-interaction

# Clear Laravel caches
echo "Clearing Laravel caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "Build completed successfully."

