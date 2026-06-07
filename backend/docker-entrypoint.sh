#!/bin/bash
set -e

# 1. Clear everything to ensure we start fresh
echo "Clearing old caches..."
php artisan config:clear
php artisan route:clear

# 2. Database Tasks
echo "Running migrations..."
php artisan migrate --force
# php artisan migrate:fresh --seed --force


# 3. Production Caching (Run AFTER migrations)
# This makes the app load significantly faster on Render
echo "Optimizing Laravel for Production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 4. Start the Queue Worker in the background
# We use --queue=default to keep it simple and fast
echo "Starting queue worker..."
php artisan queue:work --daemon --tries=3 --timeout=90 &

# 5. START THE SERVER
echo "Starting Production Server..."
# Using php -S is okay for small apps, but ensure it points to the public folder
exec php -S 0.0.0.0:8000 -t public