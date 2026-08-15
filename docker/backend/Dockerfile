FROM php:8.4-fpm-alpine

# Composer binary from the official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache \
        $PHPIZE_DEPS \
        unzip \
        curl \
    && docker-php-ext-install pdo_mysql \
    && apk del $PHPIZE_DEPS

WORKDIR /var/www

# Install dependencies first (better layer caching)
COPY backend/composer.json backend/composer.lock /var/www/
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-progress \
    && mkdir -p /var/www/bootstrap/cache /var/www/storage

# Copy the application
COPY backend/ /var/www/
COPY docker/backend/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p /var/www/storage/framework/cache/data \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
