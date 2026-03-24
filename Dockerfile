# ============================================================
# Stage 1: Build frontend assets with Node.js
# ============================================================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci

COPY vite.config.js ./
COPY resources/ ./resources/

RUN npm run build

# ============================================================
# Stage 2: Install PHP dependencies with Composer
# ============================================================
FROM composer:2 AS composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --optimize

# ============================================================
# Stage 3: Production image (PHP-FPM + Nginx)
# ============================================================
FROM php:8.2-fpm-alpine AS production

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    sqlite \
    sqlite-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    icu-libs \
    curl \
    bash

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_mysql \
    pdo_sqlite \
    mbstring \
    gd \
    zip \
    bcmath \
    opcache \
    pcntl \
    intl

# Configure PHP for production
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php.ini "$PHP_INI_DIR/conf.d/99-custom.ini"

# Configure Nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Configure Supervisor
COPY docker/supervisord.conf /etc/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Copy application from composer stage
COPY --from=composer /app .

# Copy built frontend assets from frontend stage
COPY --from=frontend /app/public/build public/build

# Copy entrypoint script
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Create required directories and set permissions
RUN mkdir -p \
    /var/data \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    database \
    && chown -R www-data:www-data \
    /var/data \
    storage \
    bootstrap/cache \
    database \
    && chmod -R 775 \
    /var/data \
    storage \
    bootstrap/cache \
    database

# Expose port (Render assigns PORT dynamically)
EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=5s --retries=3 \
    CMD curl -f http://localhost:8080/up || exit 1

ENTRYPOINT ["/entrypoint.sh"]
