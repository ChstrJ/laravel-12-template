FROM ghcr.io/devgine/composer-php:v2-php8.2-alpine AS build

WORKDIR /app

RUN echo 'Setting workdir /app...'

# Copy only composer files to leverage Docker cache
COPY composer.json composer.lock ./

RUN echo 'Copy composer json...'

# Install production dependencies
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader \
    --ignore-platform-req=ext-* \
    --no-scripts

# Copy the rest of the application
COPY . .

# Stage 2: Production image
FROM php:8.2-fpm-alpine

# Install only essential runtime dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    libpng \
    libjpeg-turbo \
    libwebp \
    freetype \
    libzip \
    libxml2 \
    oniguruma

# Install required PHP extensions (minimal set)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    xml \
    gd \
    opcache \
    bcmath

RUN echo 'Installing prod image'

COPY --from=build /app /var/www

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

RUN echo 'Setting permission'

# Expose port Laravel will run on
EXPOSE 8000

# Start Laravel app using the built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

