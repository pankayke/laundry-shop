#!/bin/bash
set -e

echo "==> Starting GeloWash Laundry Shop..."

cd /var/www/html

# Always create .env — Render injects env vars into the OS environment,
# but artisan commands need a .env file to exist.
echo "==> Preparing .env file..."

# Normalize APP_URL for Render environments.
# - If APP_URL is a bare hostname, prepend https://
# - If APP_URL is empty, derive from Render-provided host vars when available
APP_URL_VALUE="${APP_URL:-}"
if [ -z "$APP_URL_VALUE" ]; then
    if [ -n "${RENDER_EXTERNAL_URL:-}" ]; then
        APP_URL_VALUE="$RENDER_EXTERNAL_URL"
    elif [ -n "${RENDER_EXTERNAL_HOSTNAME:-}" ]; then
        APP_URL_VALUE="https://${RENDER_EXTERNAL_HOSTNAME}"
    else
        APP_URL_VALUE="http://localhost"
    fi
elif ! echo "$APP_URL_VALUE" | grep -qE '^https?://'; then
    APP_URL_VALUE="https://${APP_URL_VALUE}"
fi

{
    echo "APP_NAME=\"${APP_NAME:-GeloWash}\""
    echo "APP_ENV=${APP_ENV:-production}"
    echo "APP_DEBUG=${APP_DEBUG:-false}"
    echo "APP_URL=${APP_URL_VALUE}"
    echo "APP_KEY=${APP_KEY:-}"
    echo "DB_CONNECTION=${DB_CONNECTION:-sqlite}"
    echo "DB_DATABASE=${DB_DATABASE:-/var/data/database.sqlite}"
    echo "SESSION_DRIVER=${SESSION_DRIVER:-database}"
    echo "CACHE_STORE=${CACHE_STORE:-database}"
    echo "QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}"
    echo "MAIL_MAILER=${MAIL_MAILER:-log}"
    echo "BCRYPT_ROUNDS=${BCRYPT_ROUNDS:-12}"
    echo "LOG_CHANNEL=${LOG_CHANNEL:-stack}"
    echo "LOG_STACK=${LOG_STACK:-single}"
    echo "FILESYSTEM_DISK=${FILESYSTEM_DISK:-local}"
} > .env

cat .env
echo "==> .env created with above values."

# Create SQLite database if using SQLite and it doesn't exist
if [ "${DB_CONNECTION}" = "sqlite" ]; then
    DB_PATH="${DB_DATABASE:-/var/data/database.sqlite}"
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

# Clear any stale caches before migrating
echo "==> Clearing caches..."
php artisan config:clear
php artisan cache:clear 2>/dev/null || true

# Run database migrations FIRST (before caching)
echo "==> Running migrations..."
php artisan migrate --force
echo "==> Migrations complete."

# Auto-seed if database is empty (no users exist), or if DB_SEED=true
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null || echo "0")
echo "==> Users in database: $USER_COUNT"

if [ "$USER_COUNT" = "0" ] || [ "${DB_SEED:-false}" = "true" ]; then
    echo "==> Seeding database (empty or DB_SEED=true)..."
    php artisan db:seed --force --verbose
    USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null || echo "unknown")
    echo "==> Users after seeding: $USER_COUNT"
else
    echo "==> Database already has users, skipping seed."
fi

# NOW cache config/routes/views for production performance (after DB is ready)
echo "==> Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
echo "==> Linking storage..."
php artisan storage:link --force 2>/dev/null || true

# Ensure correct permissions after all operations
chown -R www-data:www-data storage bootstrap/cache
if [ -d /var/data ]; then
    chown -R www-data:www-data /var/data
fi

echo "==> Application ready! Starting services..."

# Start supervisord (manages nginx, php-fpm, queue worker)
exec /usr/bin/supervisord -c /etc/supervisord.conf
