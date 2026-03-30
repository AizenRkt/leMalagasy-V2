FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
        libssl-dev \
        pkg-config \
        unzip \
    && docker-php-ext-install pdo_pgsql pgsql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
