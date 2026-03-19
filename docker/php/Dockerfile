FROM php:8.2-fpm-alpine

# Install non-dev dependencies
RUN set -eux \
  && apk add --no-cache \
  git vim zip unzip bash curl tzdata icu-libs \
  c-client imap gmp gettext libssh2 \
  libintl libxslt libpng libwebp libjpeg-turbo freetype imap \
  linux-headers oniguruma libxslt libpq vips \
  libzip libxml2 freetds busybox-suid supervisor dcron libcap

# Install dependencies
RUN set -eux \
  && apk add --no-cache --virtual .build_deps \
  libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev imap-dev \
  linux-headers oniguruma-dev libxslt-dev vips-dev \
  libssh2-dev gmp-dev libzip-dev libxml2-dev freetds-dev \
  \
  && docker-php-ext-install \
  mysqli \
  pdo_mysql \
  mbstring \
  xml \
  gd \
  exif \
  zip \
  intl \
  xsl \
  sockets \
  \
  && rm -rf /tmp/pear \
  && docker-php-source delete \
  \
  # Clean up
  && apk del --purge .build_deps \
  && rm -rf /var/cache/apk/*

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy php settings
COPY ./php.ini ${PHP_INI_DIR}/conf.d/00-php.ini

# Copy entrypoint
COPY ./entrypoint /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

# Workdir
ENV WORKDIR=/var/www
RUN mkdir -p ${WORKDIR}
WORKDIR ${WORKDIR}

# Expose general port
EXPOSE 9000
# Expose websocket port
EXPOSE 6001

# Add custom user
RUN addgroup -g 1000 crm-group && adduser -G crm-group -u 1000 -h /app -s /usr/bin/bash -h /app crm-user -D

# Setup supervisor and cron directories and log file
RUN mkdir -p /etc/supervisor && \
    mkdir -p /etc/supervisor/conf.d && \
    mkdir -p /var/log/supervisor && \
    rm /etc/supervisord.conf && \
    touch /var/log/cron.log

# Place crontab file
COPY --chown=crm-user:crm-group cron/laravel-scheduler /etc/crontabs/crm-user

# Setup ownership for app, supervisor and crond (executable) directories
RUN chown -R crm-user:crm-group /var/www && \
    chown -R crm-user:crm-group /var/log/supervisor && \
    chown crm-user:crm-group /var/log/cron.log && \
    chown crm-user:crm-group /usr/sbin/crond && \
    setcap cap_setgid=ep /usr/sbin/crond

# Place supervisor config files
COPY ./supervisor/supervisord.conf /etc/supervisord.conf
COPY ./supervisor/crm-back-websockets.conf /etc/supervisor/conf.d/crm-back-websockets.conf
COPY ./supervisor/crm-back-worker.conf /etc/supervisor/conf.d/crm-back-worker.conf

USER crm-user

# Run entrypoint
CMD ["docker-entrypoint"]
