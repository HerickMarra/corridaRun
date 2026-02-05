#!/bin/sh

set -e

echo "Starting application setup..."

# Parse DATABASE_URL if provided (Render format)
if [ -n "$DATABASE_URL" ]; then
    echo "Parsing DATABASE_URL..."
    export DB_CONNECTION=pgsql
    
    # Use sed to extract components more reliably
    # Format: postgres://user:pass@host:port/database
    DB_USER=$(echo "$DATABASE_URL" | sed -n 's|postgres://\([^:]*\):.*|\1|p')
    DB_PASS=$(echo "$DATABASE_URL" | sed -n 's|postgres://[^:]*:\([^@]*\)@.*|\1|p')
    DB_HOST=$(echo "$DATABASE_URL" | sed -n 's|.*@\([^:]*\):.*|\1|p')
    DB_PORT=$(echo "$DATABASE_URL" | sed -n 's|.*:\([0-9]*\)/.*|\1|p')
    DB_NAME=$(echo "$DATABASE_URL" | sed -n 's|.*/\(.*\)|\1|p')
    
    export DB_HOST="$DB_HOST"
    export DB_PORT="$DB_PORT"
    export DB_DATABASE="$DB_NAME"
    export DB_USERNAME="$DB_USER"
    export DB_PASSWORD="$DB_PASS"
    
    echo "Database configuration parsed successfully"
fi

# Wait for database to be ready
echo "Waiting for database..."
RETRIES=30
until php artisan db:show 2>/dev/null || [ $RETRIES -eq 0 ]; do
    echo "Database is unavailable - sleeping (retries left: $RETRIES)"
    RETRIES=$((RETRIES-1))
    sleep 2
done

if [ $RETRIES -eq 0 ]; then
    echo "ERROR: Database connection timeout"
    exit 1
fi

echo "Database is ready!"

# Run migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Clear and cache config
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if it doesn't exist
if [ ! -L /var/www/html/public/storage ]; then
    echo "Creating storage link..."
    php artisan storage:link
fi

echo "Application setup complete!"

# Execute the main command
exec "$@"
