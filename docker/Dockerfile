FROM php:8.2-fpm-alpine3.18

ARG APP_ENV

# Install system dependencies
RUN apk add --no-cache \
    git \
    libzip-dev \
    unzip \
    zip

# Install PHP extensions
RUN docker-php-ext-install zip bcmath

# Install Redis extension via PECL
RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    g++ \
    gcc \
    libc-dev \
    make \
    pkgconfig \
    && yes "" | pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps \
    && echo "Redis extension installed and cleaned up"

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && echo "Composer installed"

# Set working directory
WORKDIR /app

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
