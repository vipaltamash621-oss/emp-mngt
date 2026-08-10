#!/bin/bash
set -e

echo "🚀 Starting Employee Management System..."

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready on host: $DB_HOST:$DB_PORT"
max_attempts=60
attempt=1

while [ $attempt -le $max_attempts ]; do
    if mysqladmin ping -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" --silent 2>/dev/null; then
        echo "✅ MySQL is ready!"
        break
    fi
    echo "   Attempt $attempt/$max_attempts - MySQL not ready yet..."
    sleep 1
    attempt=$((attempt + 1))
done

if [ $attempt -gt $max_attempts ]; then
    echo "❌ MySQL failed to start within timeout"
    exit 1
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

# Try to run migrations
echo "📊 Running database migrations..."
php artisan migrate --force || {
    echo "⚠️  First migration attempt failed, retrying..."
    sleep 3
    php artisan migrate --force
}

# Seed database with test data if needed
echo "🌱 Checking if database needs seeding..."
if [ -f database/seeders/DatabaseSeeder.php ]; then
    php artisan db:seed --force || true
fi

# Final cache clear
echo "🧹 Final cache clear..."
php artisan config:cache
php artisan cache:clear

echo "✅ Setup complete! Starting Apache..."
exec apache2-foreground
