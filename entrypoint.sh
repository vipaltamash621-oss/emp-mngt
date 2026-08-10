#!/bin/bash
set -e

echo "🚀 Starting Employee Management System..."

# Check if DB_HOST is set, if not, skip database operations
if [ -z "$DB_HOST" ]; then
    echo "⚠️  DB_HOST not set, skipping database initialization"
else
    # Wait for MySQL to be ready
    echo "⏳ Waiting for MySQL to be ready on host: $DB_HOST:$DB_PORT"
    max_attempts=120
    attempt=1

    while [ $attempt -le $max_attempts ]; do
        if mysqladmin ping -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" --silent 2>/dev/null; then
            echo "✅ MySQL is ready!"
            break
        fi
        if [ $((attempt % 10)) -eq 0 ]; then
            echo "   Still waiting... Attempt $attempt/$max_attempts"
        fi
        sleep 1
        attempt=$((attempt + 1))
    done

    if [ $attempt -le $max_attempts ]; then
        # Clear config cache first
        echo "🧹 Clearing config cache..."
        php artisan config:clear || true

        # Generate APP_KEY if not set
        if [ -z "$APP_KEY" ] || [ "$APP_KEY" == "no-key" ]; then
            echo "🔑 Generating APP_KEY..."
            php artisan key:generate --force
        fi

        # Wait a moment for app key to settle
        sleep 2

        # Try to run migrations (non-blocking)
        echo "📊 Running database migrations..."
        php artisan migrate --force 2>&1 || {
            echo "⚠️  Migration failed, retrying once..."
            sleep 5
            php artisan migrate --force 2>&1 || {
                echo "⚠️  Migrations failed, but continuing..."
            }
        }

        # Seed database with test data if needed
        echo "🌱 Checking if database needs seeding..."
        if [ -f database/seeders/DatabaseSeeder.php ]; then
            php artisan db:seed --force 2>&1 || true
        fi

        # Final cache clear
        echo "🧹 Final cache clear..."
        php artisan config:cache || true
        php artisan cache:clear || true
    else
        echo "⚠️  MySQL timeout, but continuing anyway..."
    fi
fi

# Ensure only one MPM is enabled
echo "⚙️  Configuring Apache MPM..."
a2dismod mpm_event mpm_worker mpm_winnt 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# Test Apache configuration
echo "🔍 Testing Apache configuration..."
if ! apache2ctl -t 2>&1 | grep -q "Syntax OK"; then
    echo "❌ Apache configuration test failed!"
    apache2ctl -t
    exit 1
fi

echo "✅ Apache configuration is valid"
echo "✅ Setup complete! Starting Apache..."

# Start Apache in foreground
exec apache2-foreground
