# Use PHP 8.3 image with Apache
FROM php:8.3-apache

# Install necessary dependencies for PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev && apt-get clean

# Install PHP extensions (PDO, PDO_PGSQL, PGSQL)
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the application code into the container
COPY . /var/www/html

# Install Composer (Laravel's dependency manager)
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install PHP dependencies via Composer
RUN composer install --no-interaction --prefer-dist

# Set the correct permissions for the storage folder (write permissions)
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 for the web server
EXPOSE 80

# Ensure Apache runs as the default entrypoint
CMD ["apache2-foreground"]
