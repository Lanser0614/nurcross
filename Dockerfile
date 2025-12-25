FROM php:8.2-fpm

# Install system dependencies and PHP extensions required by Laravel + PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" bcmath gd intl mbstring opcache pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Install Composer from the official image for reproducibility
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy the application source (for build-time composer install)
COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1

# Install PHP dependencies (skip gracefully if composer.json is missing)
RUN if [ -f composer.json ]; then \
        composer install --no-interaction --prefer-dist --optimize-autoloader; \
    else \
        echo "composer.json not found, skipping composer install"; \
    fi

# Ensure write permissions for storage/bootstrap-cache
RUN chown -R www-data:www-data /var/www/html

USER www-data
