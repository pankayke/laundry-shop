#!/bin/bash
set -e

echo "==> Starting GeloWash Laundry Shop..."

cd /var/www/html

# Create .env file from environment variables if it doesn't exist
if [ ! -f ".env" ]; then
    echo "==> Creating .env file from environment variables..."
    env | grep -E '^(APP_|DB_|MAIL_|QUEUE_|CACHE_|SESSION_|REDIS_|LOG_|BROADCAST_|FILESYSTEM_|VITE_|BCRYPT_)' | sort > .env
    # Ensure APP_KEY line exists (even if empty) so key:generate can write to it
    grep -q '^APP_KEY=' .env || echo 'APP_KEY=' >> .env
fi

# Create SQLite database if using SQLite and it doesn't exist
if [ "${DB_CONNECTION}" = "sqlite" ]; then
    DB_PATH="${DB_DATABASE:-database/database.sqlite}"
    if [ ! -f "$DB_PATH" ]; then
        echo "==> Creating SQLite database at $DB_PATH..."
        touch "$DB_PATH"
        chown www-data:www-data "$DB_PATH"
        chmod 664 "$DB_PATH"
    fi
fi

# Generate app key if not set
if [ -z "${APP_KEY}" ] || [ "${APP_KEY}" = "" ]; then
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

# Seed database if fresh (check if users table is empty)
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
