# Multi-stage build for optimized image
FROM php:8.2-apache as base

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    mariadb-client \
    nodejs \
    npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Enable Apache modules
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application first
COPY . .

# Create necessary directories and set permissions BEFORE composer
RUN mkdir -p bootstrap/cache storage/logs \
    && chmod -R 777 bootstrap/cache storage

# Copy composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set COMPOSER_ALLOW_SUPERUSER to allow root user
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist 2>&1

# Install Node dependencies and build assets
RUN npm install --production=false && npm run build

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 755 storage/ bootstrap/cache/

# Configure Apache to serve public folder
RUN sed -i 's|DocumentRoot /var/www/html/public|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/s|AllowOverride None|AllowOverride All|' /etc/apache2/apache2.conf

# Create .env file if not exists
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Copy entrypoint script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Start with entrypoint script
ENTRYPOINT ["/entrypoint.sh"]
