#!/bin/bash
set -e

echo "🚀 Starting Employee Management System..."

# Check if DB_HOST is set, if not, skip database operations
if [ -z "$DB_HOST" ]; then
    echo "⚠️  DB_HOST not set, skipping database initialization"
    echo "✅ Starting Apache without database..."
    exec apache2-foreground
fi

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

if [ $attempt -gt $max_attempts ]; then
    echo "⚠️  MySQL timeout, but continuing anyway..."
    echo "📌 Note: Database operations may fail"
fi

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

echo "✅ Setup complete! Starting Apache..."
exec apache2-foreground
