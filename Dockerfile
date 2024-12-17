# Use PHP 8.0.30 with Apache
FROM php:8.0.30-apache

# Set the maintainer (optional)
LABEL authors="Your Name"

# Install required PHP extensions
RUN docker-php-ext-install mysqli

# Copy your application code into the container
COPY app/ /var/www/html/

# Expose port 80 for HTTP access
EXPOSE 80

# Start Apache by default
CMD ["apache2-foreground"]
