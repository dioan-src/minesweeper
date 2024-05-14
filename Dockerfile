# Use official PHP 8.1.2 image as base
FROM php:8.1.2

# Set working directory inside the container
WORKDIR /var/www/html

# Copy PHP files from host to container
COPY . .

# Command to run your PHP application (modify according to your app)
CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html"]
