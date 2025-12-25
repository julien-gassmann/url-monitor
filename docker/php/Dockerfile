FROM composer:2.8 AS composer

FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    mariadb-client \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    zip \
    mbstring \
    exif \
    pcntl \
    bcmath

# Install pcov for test coverage
RUN pecl install pcov && docker-php-ext-enable pcov

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copy PHP config
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Copy entrypoint
COPY docker/php/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

WORKDIR /usr/src/app

ENTRYPOINT ["/entrypoint.sh"]