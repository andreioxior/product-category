# ========================================
# Builder Stage - Install dependencies
# ========================================
FROM php:8.4-fpm AS builder

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

# Install Redis extension via PECL
RUN pecl install redis && docker-php-ext-enable redis && echo "extension=redis.so" > /usr/local/etc/php/conf.d/docker-php-ext-redis.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy only composer files first for better layer caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# ========================================
# Production Stage
# ========================================
FROM php:8.4-fpm AS production

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

# Install Redis extension via PECL
RUN pecl install redis && docker-php-ext-enable redis && echo "extension=redis.so" > /usr/local/etc/php/conf.d/docker-php-ext-redis.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files from builder
COPY --from=builder /var/www/vendor /var/www/vendor

# Copy remaining application files
COPY . .

# Copy optimized composer autoloader
COPY --from=builder /var/www/vendor/autoload.php /var/www/vendor/autoload.php
COPY --from=builder /var/www/vendor/composer /var/www/vendor/composer

# Generate application key (only if not set)
RUN test -n "$APP_KEY" || (cp .env.example .env && php artisan key:generate)

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Install Node.js and cron for scheduler
RUN curl -sL https://deb.nodesource.com/setup_22.x | bash -
RUN apt-get install -y nodejs cron

# Install NPM dependencies and build assets
RUN npm ci --legacy-peer-deps && npm run build

# Generate autoloader
RUN composer dump-autoload --optimize

# Configure PHP OPcache for production
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.interned_strings_buffer=16" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Expose port 9000
EXPOSE 9000

CMD ["php-fpm"]

# ========================================
# Development Stage (alternative)
# ========================================
FROM php:8.4-fpm AS development

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

# Install Redis extension via PECL
RUN pecl install redis && docker-php-ext-enable redis && echo "extension=redis.so" > /usr/local/etc/php/conf.d/docker-php-ext-redis.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-scripts --prefer-dist

# Generate application key
RUN composer dump-autoload --optimize && cp .env.example .env && php artisan key:generate

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Install Node.js and NPM
RUN curl -sL https://deb.nodesource.com/setup_22.x | bash -
RUN apt-get install -y nodejs cron && mkdir -p /etc/cron.d

# Install NPM dependencies and build assets
RUN npm install && npm run build

# Generate autoloader
RUN composer dump-autoload --optimize

# Expose port 9000
EXPOSE 9000

CMD ["php-fpm"]
