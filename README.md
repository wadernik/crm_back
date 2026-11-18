# CRM backend — Laravel 13 / PHP 8.5 infrastructure skeleton

New project created from scratch (the legacy Laravel 10 application in
`../crm_back` is the reference for requirements only; nothing was migrated).

Target stack: Laravel 13, PHP 8.5, Docker Compose, PostgreSQL, Redis, RabbitMQ.
Architecture target (from the audit): modular monolith — module namespaces and
business logic land in later phases, not here.

## Runtime layout

| Piece | Value |
|---|---|
| PHP runtime | PHP 8.5.12 in the `crm-dev-app` image (`docker/php/Dockerfile`) |
| Composer | inside containers only — the host PHP cannot satisfy `composer.json` (`php ^8.5`, `ext-redis`, `ext-pdo_pgsql`, `ext-sockets`, `ext-intl`, `ext-zip`) |
| Web server | nginx → PHP-FPM (`app:9000`) |
| Database | PostgreSQL 18 (`postgres:18.6-alpine`), UTC storage |
| Cache / locks | Redis 8 (`redis:8.8.4-alpine`) |
| Messaging | RabbitMQ 4 (`rabbitmq:4.4.1-management-alpine`) via `vladimir-yuldashev/laravel-queue-rabbitmq` |
| Test runner | PHPUnit 12 in an isolated `crm-test` compose stack |

All Compose commands are run from the repository root and must pass `--env-file`,
because the Compose files live in `docker/` and Compose would otherwise look for
`docker/.env`:

```sh
docker compose --env-file .env -f docker/compose.yaml <command>
```

## 1. Environment files

Development:

```sh
cp .env.example .env
```

Automated testing (isolated stack, separate database and Redis):

```sh
cp .env.testing.example .env.testing
sed -i "s|^APP_KEY=.*|APP_KEY=base64:$(openssl rand -base64 32)|" .env.testing
```

`.env`, `.env.testing` and every other `.env.*` file are git-ignored; only the
`*.example` templates are tracked. Placeholder credentials (`change-me-*`) must be
replaced by developer-managed values locally, and production values come from the
vault. For development, `php artisan key:generate` (run inside the app container)
writes `APP_KEY` into `.env`.

## 2. Build

```sh
docker compose --env-file .env -f docker/compose.yaml build
```

Builds the application image (`docker/php`) and the nginx image (`docker/nginx`).

Install dependencies (writes `vendor/` into the bind-mounted project):

```sh
docker compose --env-file .env -f docker/compose.yaml run --rm app composer install
docker compose --env-file .env -f docker/compose.yaml run --rm app php artisan key:generate
```

## 3. Start, stop, inspect

The `Makefile` at the repository root wraps the Compose commands below; run it
from the repository root (it passes `--env-file` for you):

```sh
make up        # start the development stack, detached
make up-queue  # same, plus the profiled queue worker
make down      # remove containers, keep data volumes
make down-v    # remove containers and data volumes
make test-down # tear down the isolated test stack
make           # list the targets
```

Start the development stack (PostgreSQL, Redis, RabbitMQ, app, nginx):

```sh
docker compose --env-file .env -f docker/compose.yaml up -d
```

Optional queue consumer (only started with the `queue` profile):

```sh
docker compose --env-file .env -f docker/compose.yaml --profile queue up -d
```

There are no jobs yet, so the consumer idles. Publish/consume wiring can be
exercised against the broker with the bridge's own topology commands:

```sh
docker compose --env-file .env -f docker/compose.yaml run --rm app php artisan rabbitmq:queue-declare default
```

Status and health (the profiled `worker` only appears when the profile is passed):

```sh
docker compose --env-file .env -f docker/compose.yaml ps
docker compose --env-file .env -f docker/compose.yaml --profile queue ps
docker inspect --format '{{.Name}} {{.State.Health.Status}}' \
  crm-dev-postgres crm-dev-redis crm-dev-rabbitmq crm-dev-app crm-dev-nginx
```

Logs:

```sh
docker compose --env-file .env -f docker/compose.yaml logs app nginx rabbitmq
```

Shell access:

```sh
docker compose --env-file .env -f docker/compose.yaml exec app sh
docker compose --env-file .env -f docker/compose.yaml exec postgres psql -U crm_dev -d crm_dev
docker compose --env-file .env -f docker/compose.yaml exec redis redis-cli
docker compose --env-file .env -f docker/compose.yaml exec rabbitmq rabbitmq-diagnostics -q ping
```

Initial database schema (Laravel skeleton migrations only — users, cache, jobs):

```sh
docker compose --env-file .env -f docker/compose.yaml run --rm app php artisan migrate
```

Stop / clean up:

```sh
docker compose --env-file .env -f docker/compose.yaml stop   # keeps data volumes
docker compose --env-file .env -f docker/compose.yaml down   # remove containers
docker compose --env-file .env -f docker/compose.yaml down -v  # also remove data volumes
```

## 4. Verify the application responds

```sh
curl -i http://127.0.0.1:8000/            # nginx -> PHP-FPM -> Laravel welcome response
curl -I http://127.0.0.1:15672/           # RabbitMQ management UI (credentials from .env)
docker compose --env-file .env -f docker/compose.yaml run --rm app php artisan about
```

`php artisan about` prints the resolved environment (PHP version, drivers,
connections) and is the quickest way to confirm the app boots against PostgreSQL
and Redis.

## 5. Automated tests

The test stack is a separate Compose project (`crm-test`) with its own network,
volumes, database (`crm_testing`) and Redis instance, on host ports `5433`/`6380`
so it never touches the development stack. RabbitMQ is not part of the test stack:
tests use the synchronous queue driver.

```sh
docker compose --env-file .env.testing -f docker/compose.test.yaml build app-test
docker compose --env-file .env.testing -f docker/compose.test.yaml up --abort-on-container-exit app-test
docker compose --env-file .env.testing -f docker/compose.test.yaml down -v
```

Dependencies for the test image are installed in the development stack first
(`composer install` includes dev dependencies), so `vendor/` is already present.

## 6. Code style

```sh
docker compose --env-file .env -f docker/compose.yaml run --rm app composer lint
# declare_strict_types is a risky fixer, so it needs explicit opt-in:
docker compose --env-file .env -f docker/compose.yaml run --rm app composer format
```

## 7. Health checks and startup order

| Service | Health check |
|---|---|
| `postgres` | `pg_isready -U "$POSTGRES_USER" -d "$POSTGRES_DB"` |
| `redis` | `redis-cli ping` returns `PONG` |
| `rabbitmq` | `rabbitmq-diagnostics -q ping` |
| `app` | TCP probe on `127.0.0.1:9000` (PHP-FPM listening) |
| `nginx` | `wget` request through nginx reaches PHP-FPM and returns a response |
| `app-test` | none (short-lived runner; exits with the PHPUnit result) |

Startup order is enforced with `depends_on: {condition: service_healthy}`:
PostgreSQL / Redis / RabbitMQ → `app` → `nginx`; the optional `worker` waits for
RabbitMQ and `app`.

Host ports are published to `127.0.0.1` only. Dev: `8000`, `5432`, `6379`, `5672`,
`15672`. Test: `5433`, `6380`.

## 8. Dependencies and why

Installed in `composer.json`:

| Package | Why |
|---|---|
| `laravel/framework ^13.0` | target framework (audit Q1) |
| `laravel/tinker ^3.0` | shipped by the Laravel skeleton; keeps `artisan tinker` usable |
| `vladimir-yuldashev/laravel-queue-rabbitmq ^15.0` | Laravel has no first-party AMQP driver (audit B3); owner chose the AMQP bridge option (Q3). v15 declares `illuminate/queue ^13` compatibility |
| `php ^8.5`, `ext-pdo_pgsql`, `ext-redis`, `ext-sockets`, `ext-intl`, `ext-zip` | target PHP version and the extensions the app actually needs: PostgreSQL, Redis cache/locks, php-amqplib transport, ICU locale handling, archive handling |
| `phpunit/phpunit ^12.5.12` | test runner (owner decision Q20: PHPUnit, not Pest) |
| `nunomaduro/collision ^8.6` | readable test/exception output for `php artisan test` |
| `fakerphp/faker`, `mockery/mockery` | skeleton factories/tests and future test doubles |
| `friendsofphp/php-cs-fixer ^3.95` | formatter chosen by the owner (Q22); config `.php-cs-fixer.php` enforces PSR-12 and `declare(strict_types=1)` |
| `roave/security-advisories dev-latest` | blocks installation of packages with known advisories |

Not installed on purpose (nothing in this skeleton consumes them yet; each needs a
separate approval): `laravel/sanctum` (API auth, audit M2), `laravel/reverb`
(realtime, audit Q7), `laravel/horizon` (owner chose the AMQP bridge, Q3),
`spatie/laravel-activitylog`, `maatwebsite/excel`, `barryvdh/laravel-dompdf`,
`guzzlehttp/guzzle`, `jenssegers/agent`, `predis/predis`, `doctrine/dbal`,
PHPStan/Larastan (level not decided yet, Q23).

## 9. Known gaps in this stage

- No authentication layer yet (Sanctum 4 deferred), so no endpoint is authorised.
- No realtime layer (Reverb decision pending); `BROADCAST_CONNECTION=log`.
- No business logic, routes beyond the skeleton, or migrated database schema.
- `package.json`, `vite.config.js`, `.npmrc` and `resources/js` are retained from
  the Laravel skeleton but unused; removing them is a separate decision.
- No scheduler/worker container by default: the skeleton has no scheduled
  commands. The `queue` profile worker is available but there are no jobs yet.
- `AGENTS.md` and `CLAUDE.md` are the Laravel skeleton's own agent guidance files;
  the migration rules from the legacy project should be moved in deliberately
  later.
