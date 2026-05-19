FROM php:8.3-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    curl \
    libzip-dev \
    oniguruma-dev \
    autoconf \
    gcc \
    g++ \
    make \
    openssl-dev

# Install PHP extensions
RUN docker-php-ext-install \
    mbstring \
    zip \
    pcntl \
    bcmath

# Install MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# -------------------------------------------
# Dependencies stage
# -------------------------------------------
FROM base AS deps

COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# -------------------------------------------
# Application stage
# -------------------------------------------
FROM base AS app

# Create non-root user
RUN addgroup -S appgroup && adduser -S appuser -G appgroup

# Copy application code
COPY . .

# Copy vendor from deps stage
COPY --from=deps /var/www/html/vendor ./vendor

# Create required directories & env BEFORE autoload (package:discover needs them)
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && cp -n .env.example .env || true

# Generate autoloader (triggers package:discover)
RUN composer dump-autoload --optimize --no-dev

# Generate app key
RUN php artisan key:generate --force

# Set ownership to non-root user
RUN chown -R appuser:appgroup /var/www/html \
    && chmod -R 775 storage bootstrap/cache

USER appuser

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
