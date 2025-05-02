FROM php:8.3-cli-alpine

# PHP extensions your app needs
RUN apk add --no-cache git bash libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev \
    && docker-php-ext-install pdo_pgsql zip gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Project files
WORKDIR /app
COPY . .

# Install dependencies & optimise
RUN composer install --no-dev --optimize-autoloader \
 && if [ -f package.json ]; then npm ci && npm run build; fi \
 && php artisan config:cache route:cache view:cache

EXPOSE 80
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
