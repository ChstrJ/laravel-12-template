FROM composer:lts AS build

WORKDIR /app

RUN echo 'Setting workdir /app...'

# Copy only composer files to leverage Docker cache
COPY composer.json composer.lock ./

RUN echo 'Copy composer json...'

# Install dependencies (without dev)
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

RUN echo 'Installing deps...'

COPY . .

FROM php:8.2-fpm-alpine

RUN apt-get update && \
    apt-get install -y \ 
    bash \
    curl \
    libpng \
    libpng-dev \
    libzip-dev \
    libxml2 \
    oniguruma-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mbstring xml zip gd bcmath

RUN echo 'Installing prod image'

COPY --from=build /app /var/www

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

RUN echo 'Setting permission'

# Expose port Laravel will run on
EXPOSE 8000

# Start Laravel app using the built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

