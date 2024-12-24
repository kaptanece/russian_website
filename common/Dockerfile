# Use the official PHP image with Apache
FROM php:8.0.30-apache

# Install necessary dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    cron \
    && docker-php-ext-install mysqli \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite

# Copy app and vendor directories
COPY ./app /var/www/html/
COPY ./vendor /var/www/vendor/

# Copy cron jobs
COPY ./app/fetch-news-cron /etc/cron.d/fetch-news-cron

# Set correct permissions for cron jobs
RUN chmod 0644 /etc/cron.d/fetch-news-cron \
    && crontab /etc/cron.d/fetch-news-cron

# Set up the web server
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html

# Expose the web server port
EXPOSE 80

# Start Apache and cron services
CMD ["bash", "-c", "cron && apache2-foreground"]
