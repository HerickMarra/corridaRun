# Multi-stage Dockerfile for Laravel Production on Render with SQLite

# Stage 1: Build dependencies
FROM php:8.3-fpm-alpine AS builder

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions (only essential ones)
RUN docker-php-ext-install pdo_sqlite exif pcntl gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy package files
COPY package*.json ./

# Install Node dependencies
RUN npm ci

# Copy application files
COPY . .

# Build frontend assets
RUN npm run build

# Stage 2: Production image
FROM php:8.3-fpm-alpine

# Install runtime dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng \
    libzip \
    sqlite

# Install PHP extensions
RUN docker-php-ext-install pdo_sqlite exif pcntl gd zip

# Set working directory
WORKDIR /var/www/html

# Create necessary directories
RUN mkdir -p /var/log/supervisor /run/nginx /var/www/html/storage/app /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views \
    /var/www/html/storage/logs /var/www/html/bootstrap/cache /var/www/html/database

# Copy Docker configuration files first
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy application from builder
COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Expose port
EXPOSE 8080

# Start supervisor
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
