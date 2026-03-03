FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git curl unzip zip libzip-dev libicu-dev libpng-dev \
    && docker-php-ext-install intl zip gd pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --optimize-autoloader --no-interaction

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=${PORT}