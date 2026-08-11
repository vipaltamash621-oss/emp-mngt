#!/bin/bash

# Don't exit on error - we need to capture and debug
set +e

echo "🚀 Starting Employee Management System..."
echo "Container PID: $$"

# Function to log errors
log_error() {
    echo "[ERROR] $1" >&2
}

# Function to log info
log_info() {
    echo "[INFO] $1"
}

trap 'log_error "Script interrupted"; exit 130' INT TERM

# Generate APP_KEY if empty (CRITICAL for Laravel)
log_info "Checking APP_KEY..."
if [ -z "$APP_KEY" ] || [ "$APP_KEY" == "no-key" ]; then
    log_info "Generating APP_KEY..."
    php artisan key:generate --force || log_error "Failed to generate APP_KEY"
fi

# Check if DB_HOST is set
if [ -z "$DB_HOST" ]; then
    log_info "DB_HOST not set - skipping database initialization"
else
    log_info "Waiting for MySQL on $DB_HOST:$DB_PORT..."
    max_attempts=120
    attempt=0

    while [ $attempt -lt $max_attempts ]; do
        if mysqladmin ping -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" --connect-timeout=2 --silent 2>/dev/null; then
            log_info "MySQL is ready!"
            break
        fi
        attempt=$((attempt + 1))
        if [ $((attempt % 20)) -eq 0 ]; then
            log_info "Still waiting... ($attempt/$max_attempts)"
        fi
        sleep 1
    done

    if [ $attempt -lt $max_attempts ]; then
        log_info "Running migrations..."
        php artisan migrate --force 2>&1
        
        log_info "Seeding database..."
        php artisan db:seed --force 2>&1 || true
    else
        log_info "MySQL timeout - continuing without database"
    fi
fi

# Clear and rebuild Laravel caches
log_info "Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

log_info "Rebuilding caches..."
php artisan config:cache || true

# Verify Apache configuration
log_info "Testing Apache configuration..."
apache2ctl -t
if [ $? -ne 0 ]; then
    log_error "Apache configuration test failed!"
    apache2ctl -t 2>&1
    exit 1
fi

log_info "Apache configuration valid"

# Fix PHP-FPM socket permissions if it exists
if [ -S /var/run/php-fpm.sock ]; then
    chmod 666 /var/run/php-fpm.sock
fi

# Ensure permissions are correct
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

log_info "Starting Apache in foreground..."
log_info "Apache will listen on 0.0.0.0:80"

# Start Apache - capture any startup errors
apache2-foreground &
APACHE_PID=$!

log_info "Apache started with PID: $APACHE_PID"

# Monitor Apache process
while kill -0 $APACHE_PID 2>/dev/null; do
    sleep 5
done

log_error "Apache process exited unexpectedly!"
exit 1
