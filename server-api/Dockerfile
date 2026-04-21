FROM php:8.4-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    default-mysql-client

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --optimize-autoloader --no-interaction

EXPOSE 8000

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000