#!/bin/bash
set -e

echo "🚀 Starting Employee Management System..."

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
max_attempts=30
attempt=1

while [ $attempt -le $max_attempts ]; do
    if nc -z db 3306 2>/dev/null; then
        echo "✅ MySQL is ready!"
        break
    fi
    echo "   Attempt $attempt/$max_attempts - MySQL not ready yet..."
    sleep 2
    attempt=$((attempt + 1))
done

if [ $attempt -gt $max_attempts ]; then
    echo "❌ MySQL failed to start within timeout"
    exit 1
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating APP_KEY..."
    php artisan key:generate --force
fi

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force

# Seed database if needed (optional)
# php artisan db:seed --force

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:cache
php artisan cache:clear

echo "✅ Setup complete! Starting Apache..."
exec apache2-foreground
