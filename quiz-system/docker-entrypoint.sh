#!/bin/sh
set -e

# Run migrations
php artisan migrate --force

# Create storage symlink
php artisan storage:link || true

# Cache config/routes/views for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Laravel
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
