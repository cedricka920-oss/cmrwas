# Use official PHP Apache image
FROM php:8.2-apache

# Install PostgreSQL dev libraries and PDO drivers for both MySQL and PostgreSQL (Neon)
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy project files to Apache web root
COPY . /var/www/html/

# Set directory permissions
RUN chown -R www-data:www-data /var/www/html

# Expose default HTTP port (detected by Render)
EXPOSE 80

CMD ["apache2-foreground"]
