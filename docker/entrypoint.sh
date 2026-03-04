#!/bin/bash
set -e

echo "==> Starting GeloWash Laundry Shop..."

cd /var/www/html

# Always create .env from .env.example as a base template.
# Render injects env vars into the OS environment, which Laravel reads
# on top of .env values. The file just needs to exist for artisan commands.
echo "==> Preparing .env file..."
if [ -f ".env.example" ]; then
    cp .env.example .env
else
    touch .env
fi

# Write Render's environment variables into .env so artisan can read them
{
    echo "APP_NAME=\"${APP_NAME:-GeloWash}\""
    echo "APP_ENV=${APP_ENV:-production}"
    echo "APP_DEBUG=${APP_DEBUG:-false}"
    echo "APP_URL=${APP_URL:-http://localhost}"
    echo "APP_KEY=${APP_KEY:-}"
    echo "DB_CONNECTION=${DB_CONNECTION:-sqlite}"
    echo "DB_DATABASE=${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    echo "SESSION_DRIVER=${SESSION_DRIVER:-database}"
    echo "CACHE_STORE=${CACHE_STORE:-database}"
    echo "QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}"
    echo "MAIL_MAILER=${MAIL_MAILER:-log}"
    echo "BCRYPT_ROUNDS=${BCRYPT_ROUNDS:-12}"
    echo "LOG_CHANNEL=${LOG_CHANNEL:-stack}"
    echo "LOG_STACK=${LOG_STACK:-single}"
    echo "FILESYSTEM_DISK=${FILESYSTEM_DISK:-local}"
} > .env

# Create SQLite database if using SQLite and it doesn't exist
if [ "${DB_CONNECTION}" = "sqlite" ]; then
    DB_PATH="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    if [ ! -f "$DB_PATH" ]; then
        echo "==> Creating SQLite database at $DB_PATH..."
        mkdir -p "$(dirname "$DB_PATH")"
        touch "$DB_PATH"
        chown www-data:www-data "$DB_PATH"
        chmod 664 "$DB_PATH"
    fi
fi

# Generate Laravel app key if not already a valid base64 key
if echo "${APP_KEY}" | grep -qE '^base64:'; then
    echo "==> APP_KEY already set, skipping generation."
else
    echo "==> Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache config for production
echo "==> Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "==> Running migrations..."
php artisan migrate --force

# Seed database if requested
if [ "${DB_SEED:-false}" = "true" ]; then
    echo "==> Seeding database..."
    php artisan db:seed --force
fi

# Create storage symlink
echo "==> Linking storage..."
php artisan storage:link --force 2>/dev/null || true

# Ensure correct permissions after all operations
chown -R www-data:www-data storage bootstrap/cache database

echo "==> Application ready! Starting services..."

# Start supervisord (manages nginx, php-fpm, queue worker)
exec /usr/bin/supervisord -c /etc/supervisord.conf
