# Application image: PHP 8.5 FPM + extensions required by the skeleton.
#
# Pinned to PHP 8.5.12 (GA) - the target runtime agreed in the migration audit (Q1).
# Extensions installed here (each is required by composer.json "require"):
#   pdo_pgsql  - PostgreSQL access (owner Q2: MySQL is replaced by PostgreSQL)
#   redis      - Laravel Redis cache store and Cache::lock() (owner Q19)
#   sockets    - required by php-amqplib (pure PHP client pulled in by the RabbitMQ queue driver)
#   intl       - ICU/locale handling for the app locale (ru with en fallback, owner Q25)
#   zip        - Composer/archive handling used by tooling
# ext-amqp is intentionally NOT installed: php-amqplib works over plain sockets, and the
# PECL amqp build for PHP 8.5 is unverified. Revisit if throughput requires it.
FROM php:8.5.12-fpm-alpine

RUN set -eux \
    && apk add --no-cache \
        git \
        unzip \
        libpq \
        icu-libs \
        libzip \
    # Build dependencies for the bundled extensions (phpize toolchain is provided by
    # the PHP image as PHPIZE_DEPS; linux-headers is needed by ext-sockets).
    && apk add --no-cache --virtual .ext-deps \
        ${PHPIZE_DEPS} \
        linux-headers \
        libpq-dev \
        icu-dev \
        libzip-dev \
    && docker-php-ext-install \
        pdo_pgsql \
        intl \
        zip \
        sockets \
    # ext-redis is a PECL extension (not bundled with PHP), so it needs the phpize
    # toolchain again after docker-php-ext-install purges its own dependency set.
    && apk add --no-cache --virtual .pecl-deps \
        ${PHPIZE_DEPS} \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .ext-deps .pecl-deps \
    && rm -rf /tmp/pear /var/cache/apk/*

# Composer is used inside this image only (the target runtime is PHP 8.5;
# the host PHP 8.4 cannot satisfy composer.json platform requirements).
COPY --from=composer:2.11.2 /usr/bin/composer /usr/local/bin/composer

# PHP settings
COPY ./php.ini ${PHP_INI_DIR}/conf.d/00-laravel.ini

# Entrypoint
COPY ./docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

# Application user: uid 1000 matches the host user of the bind-mounted project,
# so files written by Composer/artisan inside the container stay usable on the host.
RUN adduser -D -H -u 1000 laravel
ENV HOME=/tmp

WORKDIR /var/www

EXPOSE 9000

USER laravel

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
