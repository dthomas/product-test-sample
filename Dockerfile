# Use an official PHP runtime as a parent image
FROM php:8.2-fpm

# Set the working directory
WORKDIR /var/www/symfony

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev

# Install PHP extensions
RUN docker-php-ext-install intl pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install application dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set permissions for Symfony
RUN chown -R www-data:www-data /var/www/symfony/var
RUN chmod -R 777 /var/www/symfony/var

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
