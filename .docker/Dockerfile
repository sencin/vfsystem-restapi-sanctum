# Use PHP 8.3 image with Apache
FROM php:8.3-apache

# Install necessary dependencies for PostgreSQL and Git
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && apt-get clean

# Install PHP extensions (PDO, PDO_PGSQL, PGSQL)
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Create a non-root user to run Composer
RUN useradd -m composer_user
USER composer_user

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the application code into the container
COPY . /var/www/html

# Install Composer (Laravel's dependency manager)
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Switch back to root user to set permissions
USER root

# Set the correct permissions for the storage and cache folders (write permissions)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Switch to the non-root user for Composer installation
USER composer_user

# Install PHP dependencies via Composer (run as non-root user)
RUN composer install --no-interaction --prefer-dist

# Set the correct permissions for the storage folder (write permissions)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 for the web server
EXPOSE 80

# Ensure Apache runs as the default entrypoint
CMD ["apache2-foreground"]
