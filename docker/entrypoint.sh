#!/bin/sh

set -e

echo "Starting application setup..."

# Ensure database directory exists and has proper permissions
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html/database
chmod -R 775 /var/www/html/database

echo "SQLite database ready!"

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
