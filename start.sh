#!/bin/bash
set -e

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is not set. Generating application key..."
    
    # Create .env file if it doesn't exist
    if [ ! -f .env ]; then
        if [ -f .env.example ]; then
            cp .env.example .env
        else
            echo "APP_KEY=" > .env
        fi
    fi
    
    # Generate the key
    php artisan key:generate --force
    
    # Extract and export the generated key
    if [ -f .env ]; then
        GENERATED_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2 | tr -d ' ' | tr -d '"')
        if [ -n "$GENERATED_KEY" ] && [ "$GENERATED_KEY" != "" ]; then
            export APP_KEY="$GENERATED_KEY"
            echo "Generated and exported APP_KEY for this session."
            echo "IMPORTANT: Set this as an environment variable in Railway dashboard for persistence:"
            echo "APP_KEY=$GENERATED_KEY"
        fi
    fi
else
    echo "APP_KEY is already set from environment variables."
fi

# Clear Laravel caches (only after APP_KEY is set)
if [ -n "$APP_KEY" ]; then
    php artisan config:clear || true
    php artisan cache:clear || true
    php artisan route:clear || true
    php artisan view:clear || true
    
    # Optimize for production
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
else
    echo "WARNING: APP_KEY is still not set. Some optimizations will be skipped."
fi

# Start the server
exec php artisan serve --host=0.0.0.0 --port=$PORT

