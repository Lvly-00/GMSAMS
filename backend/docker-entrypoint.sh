#!/bin/bash
set -e

# 1. Production Caching (Makes Laravel boot instantly)
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 2. Database Tasks
echo "Running migrations..."
# php artisan migrate --force
php artisan migrate:fresh --seed --force


# 3. Start the Queue Worker in the background
# We MUST have a worker because we changed QUEUE_CONNECTION to database
echo "Starting queue worker..."
php artisan queue:work --daemon --tries=3 --timeout=90 &

# 4. START THE SERVER
# Avoid 'artisan serve'. Use the PHP built-in server directly for slightly better perf,
# OR better yet, if your Render Plan allows, use 'php-fpm' (requires Nginx).
# For now, let's use the most optimized version of the CLI server:
echo "Starting Production Server..."
exec php -S 0.0.0.0:8000 -t public