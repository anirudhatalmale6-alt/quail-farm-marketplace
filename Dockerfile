FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git unzip libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN cp .env.render .env 2>/dev/null || cp .env.example .env 2>/dev/null || true
RUN touch database/database.sqlite
RUN php artisan key:generate --force
RUN php artisan migrate --force --seed
RUN php artisan storage:link 2>/dev/null || true

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=8080
