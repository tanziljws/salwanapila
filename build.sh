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

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is not set. Generating application key..."
    # Generate the key - this will create/update .env file if it exists
    php artisan key:generate --force
    # Get the generated key and export it for this build session
    if [ -f .env ]; then
        GENERATED_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2 | tr -d ' ')
        if [ -n "$GENERATED_KEY" ]; then
            export APP_KEY="$GENERATED_KEY"
            echo "Generated and set APP_KEY for this build session."
            echo "IMPORTANT: Set this as an environment variable in Railway dashboard for production:"
            echo "APP_KEY=$GENERATED_KEY"
        fi
    else
        echo "WARNING: .env file not found. Please set APP_KEY as an environment variable in Railway dashboard."
        echo "You can generate one locally with: php artisan key:generate --show"
    fi
else
    echo "APP_KEY is already set."
fi

# Clear Laravel caches
echo "Clearing Laravel caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "Build completed successfully."

