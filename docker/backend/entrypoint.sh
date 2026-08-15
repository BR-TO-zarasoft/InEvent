#!/bin/sh
set -e

cd /var/www

# Bootstrap the environment when none is provided (e.g. fresh clone)
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate the application key if it is not present
if [ -z "${APP_KEY:-}" ]; then
    php artisan key:generate --ansi
fi

# Keep the schema up to date
php artisan migrate --force

exec "$@"
