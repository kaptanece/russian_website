# Use PHP 8.0.30 with Apache
FROM php:8.0.30-apache

# Install required PHP extensions and cron
RUN apt-get update && apt-get install -y \
    cron \
    && docker-php-ext-install mysqli

# Copy your application code into the container
COPY app/ /var/www/html/

# Copy the crontab file into the container
COPY app/fetch-news-cron /etc/cron.d/fetch-news-cron

# Set correct permissions for the cron file
RUN chmod 0644 /etc/cron.d/fetch-news-cron

# Apply the cron job
RUN crontab /etc/cron.d/fetch-news-cron

# Start cron and Apache when the container starts
CMD ["sh", "-c", "cron && apache2-foreground"]

# Expose port 80 for HTTP access
EXPOSE 80
