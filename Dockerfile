# ───────────────────────────────────────────────────────────────
#  Dockerfile  – Drop this in your repo root, replacing the old one
# ───────────────────────────────────────────────────────────────
FROM php:8.3-cli-alpine

# ---------- system & PHP build deps ----------
RUN apk add --no-cache \
        git bash \
        autoconf make g++ \
        # image/zip libs
        libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev \
        # PostgreSQL headers & client libs  ← fixes the current error
        postgresql-dev

# ---------- PHP extensions ----------
# pdo_pgsql needs the Postgres headers we just installed
# gd needs explicit --with-* flags on Alpine
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql zip gd

# ---------- Composer ----------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ---------- project ----------
WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader \
 && if [ -f package.json ]; then npm ci && npm run build; fi \
 && php artisan config:cache route:cache view:cache

EXPOSE 80
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
