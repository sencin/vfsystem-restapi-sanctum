FROM php:8.3-apache

RUN apt-get update && apt-get install -y libpq-dev git unzip && apt-get clean

# Install PHP extensions (PDO, PDO_PGSQL, PGSQL)
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Set the correct working directory inside the container
WORKDIR /var/www/html

# Copy the application code into the container
COPY . /var/www/html

# Install Composer (Laravel's dependency manager)
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install PHP dependencies via Composer (run as non-root user)
RUN composer install --no-interaction --prefer-dist

# Set the correct permissions for the storage and bootstrap/cache folders (write permissions)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure the document root is pointing to the Laravel 'public' directory
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/apache2.conf

# Expose port 80 for the web server
EXPOSE 80

# Set the command to run Apache in the foreground
CMD ["apache2-foreground"]
