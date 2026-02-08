FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www

# Install PHP dependencies
RUN composer install --no-scripts --no-autoloader --no-interaction

# Generate autoloader
RUN composer dump-autoload

# Copy .env.example to .env if .env doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Generate application key
RUN php artisan key:generate

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 777 /var/www/storage \
    && chmod -R 777 /var/www/bootstrap/cache

# Create startup script
RUN echo '#!/bin/bash\n\
php artisan migrate --force\n\
php artisan passport:keys\n\
php-fpm\n' > /usr/local/bin/start.sh && \
chmod +x /usr/local/bin/start.sh

# Expose port 9000 for php-fpm
EXPOSE 9000

CMD ["/usr/local/bin/start.sh"]
