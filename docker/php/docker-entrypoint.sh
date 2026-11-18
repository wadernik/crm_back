#!/bin/sh
# Container entrypoint.
#
# With no arguments it starts PHP-FPM; arguments are executed verbatim, which is
# how Compose services override the command (e.g. the queue worker runs
# "php artisan queue:work rabbitmq ...").
#
# Service ordering is handled by Compose health checks (depends_on with
# service_healthy conditions), so no wait-for-dependencies logic lives here.
set -eu

if [ "$#" -eq 0 ]; then
    exec php-fpm
fi

exec "$@"
