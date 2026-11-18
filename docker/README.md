# Docker configuration

Two Compose stacks, both run from the repository root with an explicit
`--env-file` (the Compose files live in `docker/`, so Compose would otherwise
look for `docker/.env`):

```sh
docker compose --env-file .env        -f docker/compose.yaml      <command>   # local development
docker compose --env-file .env.testing -f docker/compose.test.yaml <command>  # automated testing
```

The repository root `Makefile` wraps these commands (`make up`, `make up-queue`,
`make down`, `make down-v`, `make test-down`); see the root `README.md`.

## Files

| File | Purpose |
|---|---|
| `compose.yaml` | Development stack: PostgreSQL, Redis, RabbitMQ, app (PHP-FPM), nginx, optional queue worker |
| `compose.test.yaml` | Test stack: `pg-test`, `redis-test`, `app-test` (PHPUnit runner) |
| `php/Dockerfile` | Application image: `php:8.5.12-fpm-alpine` + `pdo_pgsql`, `redis` (PECL), `sockets`, `intl`, `zip`, Composer binary |
| `php/php.ini` | Minimal PHP settings: UTC timezone, 256M memory, errors to log, OPcache |
| `php/docker-entrypoint.sh` | Starts PHP-FPM; dependency ordering is Compose's job, not the entrypoint's |
| `nginx/Dockerfile` | `nginx:1.31.8-alpine` + config copy |
| `nginx/default.conf` | Laravel vhost: `try_files` to `index.php`, FastCGI to `app:9000`, `/storage/` served from the public disk |

## Images (pinned)

| Image | Used by | Reason for the pin |
|---|---|---|
| `php:8.5.12-fpm-alpine` | app, worker, app-test | PHP 8.5 target (audit Q1); pinned to the GA patch release |
| `composer:2.11.2` | copied into the app image | Composer version used for install/update |
| `nginx:1.31.8-alpine` | nginx | newest nginx stable line |
| `postgres:18.6-alpine` | postgres, pg-test | PostgreSQL replaces MySQL (audit Q2); newest stable minor |
| `redis:8.8.4-alpine` | redis, redis-test | newest Redis stable |
| `rabbitmq:4.4.1-management-alpine` | rabbitmq | newest RabbitMQ stable, includes the management plugin/UI |

No MySQL image, no websockets server: the legacy stack's MySQL 5 service and
`beyondcode/laravel-websockets` process are not carried over (audit B8, B4).

## Services

| Service | Stack | Responsibility |
|---|---|---|
| `postgres` | dev | Primary database `crm_dev`, UTC |
| `redis` | dev | Cache store and `Cache::lock()` |
| `rabbitmq` | dev | AMQP broker, management UI on `127.0.0.1:15672` |
| `app` | dev | PHP-FPM; artisan entrypoint for one-off commands (`run --rm app ...`) |
| `nginx` | dev | HTTP entry point on `127.0.0.1:8000` |
| `worker` | dev | `php artisan queue:work rabbitmq`; behind the `queue` profile, so `up` does not start it |
| `pg-test` | test | Database `crm_testing`, host port `5433` |
| `redis-test` | test | Redis instance for test locks/cache integration, host port `6380` |
| `app-test` | test | Short-lived PHPUnit runner; exits with the test result |

## Startup order

`depends_on` uses `condition: service_healthy`:

- dev: `postgres`, `redis`, `rabbitmq` healthy → `app` → `nginx`; `worker` needs `rabbitmq` and `app` healthy
- test: `pg-test`, `redis-test` healthy → `app-test`

PostgreSQL 18+ images need a single mount at `/var/lib/postgresql` (data lives in a
major-version subdirectory); mounting `/var/lib/postgresql/data` instead makes the
container exit with a `pg_upgrade` compatibility error.

## Health checks

| Service | Command | Interval / retries / start period |
|---|---|---|
| `postgres`, `pg-test` | `pg_isready -U "$POSTGRES_USER" -d "$POSTGRES_DB"` (written as `$$...` so Compose does not interpolate it) | 5s / 5 / 10s |
| `redis`, `redis-test` | `redis-cli ping \| grep -q PONG` | 5s / 5 / 5s |
| `rabbitmq` | `rabbitmq-diagnostics -q ping` | 10s / 5 / 20s |
| `app` | `nc -z 127.0.0.1 9000` (PHP-FPM listening) | 10s / 5 / 20s |
| `nginx` | `wget -q -O /dev/null http://127.0.0.1/` (request reaches PHP-FPM) | 10s / 5 / 10s |
| `worker`, `app-test` | none — process/workload services, gated by their dependencies | — |

## Environment and secrets

Credentials are interpolated from the git-ignored env files (`${VAR:?...}` fails
loudly when a value is missing); no credentials are written into these Compose
files. Redis has no password and every host port is published to `127.0.0.1` only.

## Troubleshooting

- `docker compose --env-file .env -f docker/compose.yaml ps` — service states, including `(healthy)`
- `docker inspect --format '{{.Name}} {{.State.Health.Status}}' crm-dev-app crm-dev-nginx crm-dev-postgres crm-dev-redis crm-dev-rabbitmq`
- `docker compose --env-file .env -f docker/compose.yaml logs app nginx rabbitmq postgres redis`
- `docker compose --env-file .env -f docker/compose.yaml exec app php artisan about` — resolved runtime/drivers
- `docker compose --env-file .env -f docker/compose.yaml exec rabbitmq rabbitmq-diagnostics -q ping`
- `docker compose --env-file .env -f docker/compose.yaml exec postgres psql -U crm_dev -d crm_dev`
- Container user is uid 1000, matching the host user, so `vendor/` and `storage/`
  written from inside the container stay usable on the host.
