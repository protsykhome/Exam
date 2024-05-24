# Use the official PHP image with FPM
FROM php:7.4-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libicu-dev \
    zlib1g-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install intl pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files to the container
COPY . .

# Install PHP dependencies
RUN composer install --no-scripts --no-autoloader

# Generate optimized autoload files
RUN composer dump-autoload --optimize

# Clear and warmup Symfony cache
RUN php bin/console cache:clear --no-debug --no-warmup
RUN php bin/console cache:warmup

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
