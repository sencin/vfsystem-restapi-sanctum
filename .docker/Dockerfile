# Use an official PHP image with Apache
FROM php:8.1-apache

# Install system dependencies for PostgreSQL support
RUN apt-get update && apt-get install -y libpq-dev && apt-get clean

# Install PHP extensions (pdo_pgsql and pgsql for PostgreSQL support)
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the Laravel application into the container
COPY . /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install dependencies
RUN composer install --no-interaction --prefer-dist

# Set permissions (adjust based on your app's needs)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
