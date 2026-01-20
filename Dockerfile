FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer update --no-scripts --prefer-dist

# Generate application key
RUN composer dump-autoload --optimize && cp .env.example .env && php artisan key:generate

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www/storage bootstrap/cache

# Install Node.js and NPM
RUN curl -sL https://deb.nodesource.com/setup_22.x | bash -
RUN apt-get install -y nodejs

# Install NPM dependencies and build assets
RUN npm install && npm run build

# Generate autoloader
RUN composer dump-autoload --optimize

# Expose port 9000
EXPOSE 9000

CMD ["php-fpm"]