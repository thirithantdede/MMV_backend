# Use PHP 8.2
FROM php:8.2-fpm-alpine AS template

ARG user=mmv-user   
ARG uid=1000

RUN apk add --no-cache \
        autoconf \
        bash \
        bash-completion \
        bzip2 \
        curl \
        freetype-dev \
        gcc \
        g++ \
        git \
        jpeg-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libpq-dev \
        libtool \
        libzip-dev \
        linux-headers \
        make \
        oniguruma \
        oniguruma-dev \
        openssl \
        supervisor \
        unzip \
        wget \
        zlib-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
        bcmath \
        exif \
        gd \
        mbstring \
        opcache \
        pcntl \
        pdo \
        pdo_pgsql \
        sockets \
        zip && \
    docker-php-ext-enable  sockets

# Set the working directory
WORKDIR /var/www/mmv

# Copy composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copy source code
COPY --chown=$user:$user . /var/www/mmv

# Install composer dependencies
RUN --mount=type=cache,target=/tmp/cache \
    composer install --prefer-dist --optimize-autoloader --no-interaction && \
    composer dump-autoload --optimize

# Add user and set permission
RUN addgroup -S $user && adduser -S $user -G www-data && \
    mkdir -p /etc/supervisor /var/log/supervisor && \
    chown -R $user:www-data /usr/local/var/log /etc/supervisor /var/log/supervisor && \
    chmod -R 775 /var/www/mmv/storage /usr/local/var/log /etc/supervisor /var/log/supervisor

# Copy custom PHP-FPM configuration
COPY deployment/config/fpm/custom-php-fpm.conf /usr/local/etc/php-fpm.d/
COPY deployment/config/php/php.ini /usr/local/etc/php/php.ini

# Copy entrypoint script and ensure it's executable
COPY deployment/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

FROM template AS api

USER $user

EXPOSE 9002

CMD ["sh", "-c", "/entrypoint.sh"]  