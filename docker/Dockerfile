# -------------------------------
# Stage 1: Builder
# -------------------------------
FROM php:8.4-cli AS builder

WORKDIR /usr/src

# Install build dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    autoconf \
    bison \
    re2c \
    pkg-config \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libpq-dev \
    libsqlite3-dev \
    default-mysql-client \
    git \
    curl \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# -------------------------------
# Install PHP extensions
# -------------------------------
RUN docker-php-ext-install \
    pdo_pgsql \
    pdo_mysql \
    pdo_sqlite \
    mbstring \
    sockets \
    opcache

# -------------------------------
# Install Swoole
# -------------------------------
RUN pecl install swoole \
    && docker-php-ext-enable swoole

# -------------------------------
# Install Composer inside builder
# -------------------------------
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && HASH="$(curl -sS https://composer.github.io/installer.sig)" \
    && php -r "if (hash_file('sha384','composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# -------------------------------
# Stage 2: Runtime (Clean image)
# -------------------------------
FROM php:8.4-cli AS runtime

WORKDIR /var/www/pulse

# Install runtime libraries
RUN apt-get update && apt-get install -y \
    libpq5 \
    libsqlite3-0 \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Copy PHP extensions and binaries from builder
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
COPY --from=builder /usr/local/bin/php /usr/local/bin/php
COPY --from=builder /usr/local/bin/phpize /usr/local/bin/phpize
COPY --from=builder /usr/local/bin/php-config /usr/local/bin/php-config
COPY --from=builder /usr/local/include/php /usr/local/include/php
COPY --from=builder /usr/local/lib/php /usr/local/lib/php

# Copy Composer
COPY --from=builder /usr/local/bin/composer /usr/local/bin/composer

# -------------------------------
# Configure php.ini in runtime
# -------------------------------
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini \
    && echo "memory_limit=512M" >> /usr/local/etc/php/php.ini \
    && echo "max_execution_time=0" >> /usr/local/etc/php/php.ini \
    && echo "display_errors=On" >> /usr/local/etc/php/php.ini \
    && echo "log_errors=On" >> /usr/local/etc/php/php.ini \
    && echo "error_log=/var/log/php_errors.log" >> /usr/local/etc/php/php.ini

# -------------------------------
# Initialize Composer if needed
# -------------------------------
COPY composer.json composer.lock* /var/www/pulse/
COPY ./ /var/www/pulse

RUN composer install --no-interaction --prefer-dist


# -------------------------------
# Expose default OpenSwoole port
# -------------------------------
EXPOSE 9501

# -------------------------------
# Default command
# -------------------------------
CMD ["php", "/var/www/pulse/bin/server.php"]
