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

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the application code into the container
COPY . /var/www/html

# Install Composer (Laravel's dependency manager)
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set the correct permissions for the storage and cache folders (write permissions)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Install PHP dependencies via Composer as root (this will avoid permission issues)
USER root
RUN composer install --no-interaction --prefer-dist

# Set permissions for the storage and cache directories after install
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Set the correct permissions for the entire project
RUN chown -R www-data:www-data /var/www/html

# Update Apache configuration to set ServerName and DirectoryIndex
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    echo "DirectoryIndex index.php index.html" >> /etc/apache2/apache2.conf

# Allow Apache to access the files
RUN echo '<Directory /var/www/html>' >> /etc/apache2/apache2.conf && \
    echo '    AllowOverride All' >> /etc/apache2/apache2.conf && \
    echo '    Require all granted' >> /etc/apache2/apache2.conf && \
    echo '</Directory>' >> /etc/apache2/apache2.conf

# Expose port 80 for the web server
EXPOSE 80

# Ensure Apache runs as the default entrypoint
CMD ["apache2-foreground"]
