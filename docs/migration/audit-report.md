# Audit Report — Laravel 10.50.2 → Laravel 13 / PHP 8.5 (`dev/crm_back`)

**Mode:** read-only static audit. No code, config, or dependency changes were made.
**Repo state:** branch `v2` (HEAD `84bb160`, 2026-08-12), working tree clean except untracked `docs/`, `data/`, `docker/mysql/mysql.conf`, `storage/framework/testing/`.
**Deliverable:** this single file (`docs/migration/audit-report.md`) — audit + component mapping + owner questions.
**Excluded by instruction:** `docs/migration-deprecated/*` (a previous audit report and component mapping exist there; they were deliberately **not read** and did not influence this report).

---

## 0. Method and limits (read this first)

**What was used:** direct file reads (`app/`, `config/`, `routes/`, `database/`, `docker/`, `composer.json`, `composer.lock`, `.env*`, `phpunit.xml`), `git log`/`git ls-files`, `php -l` syntax lint of all 245 PHP files, and targeted external verification of upstream package constraints (packagist metadata) and Laravel upgrade guides (11.x, 12.x, 13.x docs).

**Limits — these are real gaps, not oversights:**

| Gap | Consequence |
|---|---|
| `vendor/` is **not installed** and `composer` is not installed on this host | `php artisan route:list`, `artisan test`, and real dependency resolution could not be run. Route inventory is a **static parse of `routes/api.php`**, not the framework's own output. |
| `tests/` contains **zero tracked files** (`git ls-files tests/` → empty; only untracked `tests/App/`) | There is no executable automated verification today; no regression safety net exists for this migration. |
| Static analysis only (no runtime, no DB) | Anything behavioural (response shapes, permission enforcement at runtime, Dooglys/VK payloads, queue semantics) is marked **hypothesis**, not fact. |
| Business intent is not written down anywhere in the repo (README is generic Laravel boilerplate) | Business rules are **reconstructed from code**; every reconstruction is labelled `[Hypothesis]`. |

**Evidence convention:** `[Fact]` = verifiable in this repo (file:line or lock/config value). `[Hypothesis]` = inference that needs owner confirmation or a runtime check. `[External]` = verified against upstream metadata/docs, cited in §8.

---

## 1. Repo structure, conventions, versions

### 1.1 Framework / runtime / package versions

| Item | Declared (`composer.json`) | Locked (`composer.lock`) | Runtime here | Evidence |
|---|---|---|---|---|
| PHP constraint | `^8.1.0` | platform `PHP 8.4.22` | PHP **8.4.22** (host) | `composer.json:8`, `composer.lock` platform section |
| PHP in Docker | — | `FROM php:8.2-fpm-alpine` | container PHP **8.2** | `docker/php/Dockerfile:1` |
| `laravel/framework` | `^10.0` | **10.50.2** | — | `composer.json:14`, lock |
| `laravel/sanctum` | `^3.2` | 3.3.3 | — | `composer.json:15` |
| `maatwebsite/excel` | **`3.1.67` (exact pin)** | 3.1.67 | — | `composer.json:17` |
| `doctrine/dbal` | `^3.6` | 3.10.5 | — | `composer.json:11` |
| `phpunit/phpunit` | `^9.5.10` | 9.6.34 | — | `composer.json:28` |
| `beyondcode/laravel-websockets` | `^1.14` | 1.14.1 | — | composer.json |
| `pusher/pusher-php-server` | `^7.2` | 7.2.7 | — | composer.json |
| `spatie/laravel-activitylog` | `^4.8` | 4.12.1 | — | composer.json |
| `barryvdh/laravel-dompdf` | `^2.0` | 2.2.0 | — | composer.json |
| `nesbot/carbon` | transitive | 2.73.0 | — | lock |
| `symfony/*` | transitive | console/process/rstring **6.4.35**, translation 6.4.33 | — | lock |
| `guzzlehttp/guzzle` | `^7.4` | 7.10.0 | — | composer.json |
| `fruitcake/laravel-cors` | `^3.0` | 3.0.5 | installed but **not wired** (see §2.2) | composer.json, `app/Http/Kernel.php:36` |
| `jenssegers/agent` | `^2.6` | 2.6.4 | used in `ProfileService` | composer.json |
| `roave/security-advisories` | `dev-latest` | — | dependency gate | composer.json |
| `laravel/tinker` | `^2.8` | 2.10.2 | — | composer.json |
| **Not present** | `laravel/horizon`, `predis/predis`, any AMQP/RabbitMQ package, any Redis queue package | — | Redis **config files exist** but no client package | `config/queue.php:65-72`, `config/database.php:120-136` |

### 1.2 Directory shape (what actually exists)

```
app/
  Http/         91 files, 2725 LOC  controllers (59), requests (17), responses, middleware (4), traits
  Repositories/ 61 files, 2713 LOC  AbstractRepository + per-entity repos/interfaces + Filter/Sort processors
  Services/     62 files, 2420 LOC  business services: Order, Activity, Auth, Board, Dooglys, VK, Profile, NumberGenerator, Price, Checker
  Models/       36 files, 1574 LOC  Eloquent models + Contracts (interfaces) + Traits
  DTOs/         23 files, 556 LOC   DataTransferObject base + per-entity DTOs + Order/V2 item DTOs
  Managers/     14 files, 1374 LOC  transaction orchestration (ManagerExtension, OrderCompositeManager, Dooglys managers…)
  Processor/    11 files, 713 LOC   creation/update orchestration (ProcessorInterface + ProcessorExtension)
  Integration/   8 files, 498 LOC   Dooglys API client, DTOs, factories, request builders
  Events/        6 files, 223 LOC   5 domain-ish events + OrderEvent enum
  Listeners/     6 files, 183 LOC
  Jobs/          6 files, 157 LOC
  Console/       9 files, 365 LOC   Kernel + 6 commands
  Preparer/      2 files, 70 LOC    attribute preparers (unused in live paths)
  Factory/       2 files, 77 LOC    RequestBuilderFactory (unused in live paths)
  Providers/     8 files, 354 LOC   RouteServiceProvider, OrderServiceProvider, OrderEntityEventServiceProvider, SanctumPermissionsServiceProvider, CommentsServiceProvider, DooglysServiceProvider,VKServiceProvider, AppServiceProvider
  Attributes/    1 file, 33 LOC     #[Permission] PHP attribute
  Helpers/       4 files, 229 LOC   autoloaded via composer `autoload.files`
bootstrap/app.php (19 lines), public/index.php, config/*.php (23 files), routes/ (4 files), database/ (44 migrations, 15 seeders), docker/, storage/
```

### 1.3 Conventions currently in force (patterns the code actually uses)

1. **Interface-first wiring, bound in providers.** Almost every service/repository has an `…Interface.php` sibling, bound one-to-one in providers (`app/Providers/RouteServiceProvider.php:23-146`). Sub-interfaces are *also* bound separately (`OrderRepositoryInterface`, `OrderFilterInterface`, `OrderLimitInterface`, `OrderOffsetInterface`, `OrderSortInterface`, `OrderItemRepositoryInterface`, `OrderContactRepositoryInterface`) — because `AbstractRepository` implements the filter/sort/limit/offset interfaces via **traits**.
2. **Repository trait-pool.** `app/Repositories/AbstractRepository.php` composes `DBFacadeTrait`, `FilterInterfaceTrait`, `LimitInterfaceTrait`, `OffsetInterfaceTrait`, `SortInterfaceTrait`, `ModelFinderTrait`; it holds a mutable `$this->builder` and calls `resetBuilder()` after each operation. Repositories are resolved per-request (`bind`, not `singleton`).
3. **Two generations of HTTP controllers coexist.**
   - **Legacy style:** class methods (`index/store/update/destroy`) extending `AbstractApiController`, manual `isAllowed('perm.name')` guards, `$requestData = $request->validated()`.
   - **V2 style:** single-method invokable controllers (`__invoke`) with method-injected services/repositories and `#[Permission('orders.view')]` attributes (enforced by `SanctumPermissions` middleware via reflection).
   - Both are live in `routes/api.php`; V2 covers orders/order-drafts/settings/order-products/delivery/reports; legacy covers users/sellers/manufacturers/roles/dictionaries/board/notifications/upload/auth.
4. **DTO layer is thin and mostly an array bag.** `DataTransferObject` (`app/DTOs/DataTransferObject.php`) = `array $attributes` + magic `__get/__set` + `getArrayProperties()`. It carries no value semantics and no validation.
5. **Validation rules are duplicated, not shared.** `Services/Order/Checker/*` re-implement the same date-window logic as `Http/Requests/Order/CreateOrderRequest.php` (`isDateLimitFull`, `isDateLimitPartial`).
6. **Global helper functions autoloaded.** `composer.json` `autoload.files` → `app/Helpers/Functions/helpers.php`, `app/Helpers/Validation/helpers.php`.
7. **Permissions = Sanctum token abilities.** On token creation, the user's role permissions are flattened into token abilities (`app/Services/Auth/AuthUserService.php:46-53`), then checked either by middleware (`app/Http/Middleware/SanctumPermissions.php`) or `AbstractApiController::isAllowed()` reading `tokenCan()` through the current token.
8. **Activity logging is ambient.** `activity()->disableLogging()` around DB transactions in managers (`app/Managers/OrderCompositeManager.php`, `OrderDraftManager.php`), explicit `describeActivity()` calls in controllers/services.
9. **Broadcast + DB notifications coexist.** `DatabaseNotificationsHandler` + `NotificationEventListener` + `BroadcastEventListener`, and events dispatched inside DB transactions.
10. **Queued side-effects use `SerializesModels`.** Jobs serialize `Order`, `User`, `DooglysMenuImportDTO`, `DooglysSalePointImportDTO` models.

---

## 2. Runtime surface inventory

### 2.1 HTTP routes (`routes/api.php`, static parse)

**Active endpoints: 106** — GET 54, POST 24, PUT 14, DELETE 14. Plus 1 commented-out endpoint (`DELETE /vk/logout`, `routes/api.php:88`) and **one entirely commented route group** (task-boards products/dictionaries/tasks/attachments, `routes/api.php:289-334`) — the TaskBoard domain has controllers + requests + repositories **implemented but unrouted**.

Prefix groups (line refs in `routes/api.php`):

| Group | Lines | Handlers | Notes |
|---|---|---|---|
| `/auth` login, devices, logout device | 77-80 | `AuthController` | public (no auth) |
| `/profile` | 93-95 | `ProfileController` | auth; `jenssegers/agent` UA parsing |
| `/vk/*` | 82-89 | **commented** | see §4.13 (integration is broken) |
| `/notifications` (+V2 unread-count) | 102-110 | legacy `NotificationController` + V2 count | `notifications.view` permission exists in seeder but is **not enforced** |
| `/import` dooglys menu / salepoint | 112-117 | V2 controllers | dispatches queued import jobs |
| `/reports` order + accepted | 120-125 | V2 | Excel/PDF export |
| `/deliveries` (`sanctum.permissions`) | 133-139 | V2 | `order.delivery.courier.edit` |
| `/dictionary/*` | 142-172 | 11 dictionary controllers | users, manufacturers, limits, roles, sellers, order-statuses, order-attributes, units, activity, order-settings, order-products |
| `/users` + reports/export | 174-180 | legacy | `users.view/edit`; reports need `reports.view` **AND** `users_report.view` |
| `/manufacturers` + `/manufacturers/limits` | 182-196 | legacy | limits guarded by `orders.view` / `orders.stop.edit` (not `manufacturers.*`) |
| `/roles` | 198-204 | legacy | `roles.view/edit` |
| `/sellers` | 206-220 | legacy | `sellers.view/edit`; dictionaries guarded by `sellers.view` |
| `/settings` (`sanctum.permissions`) | 222-233 | V2 | `settings.view/edit` |
| `/orders` (+products) | 238-269 | V2 | `orders.view/edit/process`, `comments.*`, product ops `orders.view/edit/process` |
| `/task-boards/boards`, `/groups`, `/tasks` | 272-286 | legacy `BoardController`, `GroupController`, `TaskController` | `boards.*`, `boards.group.*` |
| task-boards products/dictionaries/tasks/attachments | 289-334 | **commented** | dead routes |
| `/upload` | 337-340 | `UploadController` | **no permission check** (`files.*` permissions exist in seeder, unused) |

`routes/web.php` is a stub (`Route::get('/')` → `welcome` view, unused by the SPA); `routes/channels.php` = 3 private channels (`user/{userId}`, `order.{orderId}`, `notification.{id}`); `routes/console.php` declares `user:online-cache-flusher` **independently** of `app/Console/Commands/User/OnlineCacheFlusherCommand.php` (duplicate registration of the same command name — one wins; `Kernel.php:24` schedules it).

### 2.2 Middleware / bootstrap

- `app/Http/Kernel.php` (Laravel 10 style) — global stack replaces the framework's `HandleCors` with a custom `App\Http\Middleware\CorsHandler` that writes headers with raw `header()` calls (`Kernel.php:36`); `fruitcake/laravel-cors` stays installed but unused. `config/cors.php` exists (`allowed_origins: ['*']`) but is never consumed by `CorsHandler`.
- API group adds `ForceJsonResponse` and `UserActivity` (`Kernel.php:63-64`); custom `sanctum.permissions` alias (`Kernel.php:85`).
- A custom `$middlewarePriority` (`Kernel.php:88+`) places `ForceJsonResponse` first, ahead of `TransformRequest`/`Authenticate` — this is the load-bearing trick that makes attribute-based permission checks see an authenticated token.
- Sanctum guard config: single guard `web` (`config/sanctum.php`); token lifetimes are **never expired** (no token TTL logic; see §4.11).
- `bootstrap/app.php` is the classic Laravel 10 19-line file. `config/app.php` still declares an explicit `providers` array (`config/app.php:139`) **plus** `aliases` (`Illuminate\Support\Facades\...`) — an unusual explicit alias block that must be preserved or intentionally removed during migration.

### 2.3 Service providers

| Provider | What it wires | Live? |
|---|---|---|
| `AppServiceProvider` | `AbstractApiController::setResponse()` (static shared `ApiResponse`), `ArrayValidationMiddleware` in `ConvertEmptyStringsToNull` middleware chain, `UserModelEventListener` bound to `OrderModelObserver` | yes |
| `RouteServiceProvider` | ~120 bindings: repositories, services, managers, import/counter/export service interfaces, board managers/repos | yes |
| `OrderServiceProvider` | legacy `OrderRepositoryInterface` → `Repository`, `OrderUpdaterInterface` → `OrderUpdaterService`, `OrderEntityRepositoryInterface` → `OrderRepository` (the V2 entity repo) | partially (legacy bindings only reachable from unrouted controllers) |
| `OrderEntityEventServiceProvider` | `OrderEntityCreated/Updated → OrderEntityEventListener` | yes |
| `SanctumPermissionsServiceProvider` | middleware alias + `PermissionDenyException` handler → 403 JSON | yes |
| `DooglysServiceProvider` | Dooglys client/interface, DTO factory, sale-point & menu preparers, `MenuFactoryInterface`→`MenuFactory`, `MenuImportServiceInterface` | yes |
| `VKServiceProvider` | `VKServiceInterface`→`VkService`, `VKApiClientInterface`→`VkApiClient` | registered, **routes commented**, and interface/client mismatch (§4.13) |
| `CommentsServiceProvider` | comments bindings | **never referenced** (dead) — bindings come from `RouteServiceProvider` |
| Framework providers in `config/app.php` | includes `NotificationsServiceProvider`, `BroadcastServiceProvider`, etc. | yes |

### 2.4 Console commands & scheduled tasks

| Command | Signature | Scheduled | Evidence |
|---|---|---|---|
| `user:online-cache-flusher` | declared twice (`Kernel.php:24` + `routes/console.php:7`) | every 15 min | [Fact] |
| `orders:drafts:clear-deleted` | `ClearDeletedOrderDraftsCommand` | daily | `Kernel.php:25` |
| `notifications:clear-unread` | `ClearIrrelevantUnreadNotificationsCommand` | daily | `Kernel.php:27` |
| `notifications:clear-read` | `ClearIrrelevantReadNotificationsCommand` | daily | `Kernel.php:28` |
| `orders:notification-timeout-push` | `OrderTimeoutNotificationPusherCommand` | **commented out** (`Kernel.php:26`) | [Fact] command exists, scheduler disabled |
| `dooglys:import:menu` | `Dooglys/Menu/ImportMenuCommand` | not scheduled | manual only |
| `dooglys:import:salepoints` | `Dooglys/SalePoint/ImportSalePointsCommand` | not scheduled | manual only |

**Scheduled-task dependencies:** unread cleanup reads `SettingTypeEnum::NOTIFICATION__UNREAD_TIMEOUT`; read cleanup reads `SettingTypeEnum::NOTIFICATION__READ_TIMEOUT` — the read command looks up `SettingTypeEnum::id(NOTIFICATION__UNREAD_TIMEOUT->value)` and then compares `created_at + read_seconds` (a value/enum mismatch worth verifying); drafts cleanup deletes orders with status `draft` older than the `order_draft_deletion_timeout` setting.

### 2.5 Jobs

| Job | Queued from | Notes |
|---|---|---|
| `DooglysMenuImportJob` | `ImportDooglysMenuController` (`routes/api.php:114`) | `SerializesModels`; `queue = dooglys_import`; the controller's own dispatch line is **commented out** and it dispatches `DooglysSalePointImportJob` instead → `/import/dooglys/menu` currently runs the **sale-point** job (see §4.12) |
| `DooglysSalePointImportJob` | `ImportDooglysSalePointController` + the menu controller | same queue |
| `NotificationEventListener` (queued listener) | order/notification events | `SerializesModels`, `tries = 5` |
| `BroadcastEventListener` (queued listener) | order/notification events | `SerializesModels`, `tries = 5` |
| `OrderOverdueNotificationJob` | only from `OrderTimeoutNotificationPusherCommand` (**scheduler commented out**) | `queue = overdue_notifications`, `tries = 1`, implements `release()` to re-queue after a `notification_overdue_timeout` setting → recurring-notification-by-release pattern |
| `SyncOrderFinalPriceJob` | only from `BaseOrderUpdaterService` (legacy `OrderUpdaterService`) | reachable only through **unrouted** legacy `OrderController` (§4.14) |

Queue config: default connection **`sync`** (`config/queue.php:19`, `.env.example:42`); connections `sync`, `database` (jobs table migration exists), `redis`; supervisor runs `php artisan queue:work --tries=3` (`docker/php/supervisor/laravel-worker.conf`); failed-jobs table migration exists.

### 2.6 Events & listeners

| Event | Dispatch sites | Listeners | Effect |
|---|---|---|---|
| `OrderEntityCreated` / `OrderEntityUpdated` | `OrderCreatorProcessor`, `OrderUpdaterProcessor`, `OrderDraftCreatorProcessor`, `OrderDraftUpdaterProcessor`, `OrderCreatorService`, `OrderDraftCreatorService`, `OrderDraftUpdaterService`, `OrderDeliverySetCourierService` | `OrderEntityEventListener` (queued) | loads **all users** (`UserRepository::findAllBy([])`) → per-user DB notification rows + per-user broadcast |
| `OrderNotificationEvent` (enum `OrderEvent`) | `OrderCreatorProcessor:55`, `OrderDraftCreatorProcessor:43`, `OrderUpdaterProcessor:60` | `NotificationEventListener` (queued), `BroadcastEventListener` (queued) | same fan-out over all users |
| `NotificationEvent` | `NotificationManager` (order-draft created), `DooglysMenuManager` (menu import) | same two listeners |
| `UserRevokeAllTokensEvent` | `UserController::update` / `UserController::destroy` (`routes/api.php:178,180`) | `UserRevokeAllTokensDueToPermissionsUpdate` (queued) | iterates **all users** in 100-row chunks and revokes **every token of every user** |
| `VKUserAuthEvent` | `VKAuthController` (**unrouted**) | `VkUserTokenSaved` | saves VK token |

Listeners are queued, dispatched inside DB transactions, and use `SerializesModels` — a documented Laravel footgun (§8.5).

### 2.7 Notifications and broadcasting

- `App\Notifications\OrderDatabaseNotification` → `database` channel only; `broadcast` channel is **commented out**. Payload: `{type, id, order_id, order_number, status, user_id}`.
- Delivery is manual: `NotificationRepository` writes rows into the standard `notifications` table (`unread` column), and `NotificationManager::sendNotification()` wraps `Notify::send($user, $notification)` inside a DB transaction.
- Broadcasting path exists (`config/broadcasting.php` pusher + `config/websockets.php`, `BroadcastEventListener`), but `.env.example` sets `BROADCAST_DRIVER=log` — so the broadcast path is effectively inert unless production overrides it. `[Hypothesis]` production uses pusher/websockets.
- Channels: `user.{userId}`, `order.{orderId}`, `notification.{id}`; socket auth via private channels, no `Broadcast::authorize` guards beyond defaults (hypothesis: front-end relies on channel name only).

---

## 3. Business modules and coupling

### 3.1 Modules identifiable from code (not from directories)

| Module | Files / LOC | Code locations |
|---|---|---|
| **Orders (core)** | ~110 files, ~7.6k LOC | `Http/Controllers/Api/Order*` (incl. V2 + product + delivery + report), `Requests/Order*`, `Repositories/Order*`, `Services/Order*` (checker, export, counter, activity, price, delivery, comments, contact-type dictionary), `Managers/Order*`, `Processor/Order*`, `Models/Order*`, `DTOs/Order*` (+ V2 item DTOs), `Preparer/Order*`, `Events/Order*`, `Jobs/OrderOverdueNotificationJob`, `Console/Commands/Order*` |
| **Order drafts** | ~15 files, ~900 LOC | `Controllers/Api/OrderDraft/V2`, `Requests/OrderDraft*`, `Repositories/OrderDraft*`, `Services/OrderDraft*`, `Manager/OrderDraftManager`, `Processor/Order/OrderDraft*`, `Console/Commands/Order/Drafts/*` |
| **Delivery / courier** | ~10 files, ~450 LOC | `Controllers/Api/Delivery`, `Services/Order/Delivery/*`, `Repositories/Order/{OrderDeliveryRepository,UserDeliveriesRepository}`, `Models/OrderDelivery` |
| **Catalog: product titles (order products)** | ~20 files, ~700 LOC | `Controllers/Api/Order/Product*`, `Repositories/Order/{OrderItemRepository,OrderItemDictionaryRepository}`, `Services/Order/OrderProduct*`, `Enums/Order/Product/{ProductTitle,Decoration}`, `Models/OrderItem` |
| **Counterparties: sellers** | ~14 files, ~600 LOC | `Controllers/Api/Seller*`, `Repositories/SellerRepository`, `Services/Dooglys/*` (sale-point import), `Models/Seller` |
| **Counterparties: manufacturers + date limits** | ~14 files, ~600 LOC | `Controllers/Api/Manufacturer*`, `ManufacturerDateLimit/*`, `Repositories/Manufacturer*`, `Models/Manufacturer`, `Models/ManufacturerDateLimit`, `Services/Order/Checker/OrderCreationRestrictionByManufacturerChecker` |
| **Identity & access** | ~20 files, ~900 LOC | `Controllers/Api/{Auth,User,Role,Permission,Profile}Controller*`, `Services/Auth/AuthUserService`, `Attributes/Permission`, `Middleware/SanctumPermissions`, `Models/{User,Role,Permission,PermissionSection}`, `Repositories/{UserRepository,RoleRepository,PermissionRepository}`, token migrations |
| **Notifications** | ~12 files, ~450 LOC | `Controllers/Api/Notification*`, `Repositories/NotificationRepository`, `Manager/NotificationManager`, `Notifications/OrderDatabaseNotification`, `Events/NotificationEvent`, `Listeners/{Notification,Broadcast}EventListener`, `Console/Commands/Notification/*` |
| **Task board** | ~40 files, ~1200 LOC | `Controllers/Api/Board/{Board,Group,Task}Controller` (+ unrouted `Task{,Attachment,Content,Dictionary}Controller`), `Repositories/Board/*`, `Manager/Board/*`, `Models/{Board,BoardGroup,Task,TaskAttachment,TaskContent}`, `DTOs/Board/*`, seeders |
| **Files / uploads** | ~8 files, ~300 LOC | `Controllers/Api/Upload/*`, `Services/Upload/{UploadManager,UploadManagerInterface}`, `Models/File`, `Repositories/FileRepository`, `DTOs/Upload/*` |
| **Reporting / export** | ~14 files, ~800 LOC | `Services/Order/Export/{OrderReportExporterService,AcceptedOrdersReportService,AcceptedOrdersReportPdfService}`, `Controllers/Api/Order/Report/*`, `Controllers/Api/User/{ListUserReportController,ExportUserReportController}`, `Services/Order/{OrderReportCounterService,AcceptedOrdersReportCounterService,OrderCounterService,OrderFindWithCommentService}` |
| **Settings (system settings, incl. business timers)** | ~12 files, ~400 LOC | `Controllers/Api/Setting/*`, `Repositories/SettingRepository`, `Enums/Setting/{SettingType,SettingTypeEnum,SettingValue}`, `Models/Setting` |
| **Activity / audit** | ~12 files, ~500 LOC | `Controllers/Api/Activity/*`, `Services/Activity/*`, `Services/Order/Activity/*`, `DTOs/Activity/*`, Spatie activitylog |
| **External integrations** | ~16 files, ~700 LOC | `Integration/Dooglys/*`, `Services/Dooglys/*`, `Services/VK/*`, `Models/{UserVkToken,VkUser}`, `Jobs/Dooglys*`, `Console/Commands/Dooglys/*`, config `dooglys.php`, `vk.php` |
| **Shared kernel** | ~30 files, ~700 LOC | `Http/Responses/{ApiResponse,ApiResponseInterface}`, `Http/Traits/{ResponseTrait,DBFacadeTrait}`, `Helpers/*`, `DTOs/DataTransferObject`, `Repositories/AbstractRepository`, `Models/{Contracts,Traits}`, `Exceptions/*`, `Attributes/Permission` |

### 3.2 Dependency matrix (namespace → namespace, import edges)

Measured by counting `use App\…` statements across 245 PHP files.

| Source ↓ / Target → | Models | DTOs | Repositories | Services | Managers | Processor | Integration | Http | Console | Providers |
|---|---|---|---|---|---|---|---|---|---|---|
| **Http** | 30 | 13 | 46 | 13 | 12 | 3 | 2 | 12 | 0 | 0 |
| **Services** | 42 | 25 | 33 | 31 | 8 | 0 | 12 | 1 | 0 | 0 |
| **Repositories** | 40 | 6 | 20 | 6 | 0 | 3 | 0 | 0 | 0 | 0 |
| **Managers** | 15 | 11 | 12 | 6 | 4 | 0 | 0 | 0 | 0 | 0 |
| **Processor** | 10 | 4 | 7 | 12 | 4 | 4 | 0 | 0 | 0 | 0 |
| **Console** | 2 | 2 | 5 | 4 | 1 | 0 | 0 | 1 | 3 | 0 |
| **Jobs/Listeners** | 5 | 5 | 0 | 2 | 2 | 0 | 2 | 0 | 0 | 0 |
| **Models** | 0 | 1 | 0 | 0 | 1 | 0 | 0 | 3 | 0 | 0 |
| **Integration** | 4 | 9 | 2 | 0 | 0 | 1 | 4 | 0 | 0 | 0 |
| **DTOs** | 1 | 0 | 0 | 0 | 0 | 0 | 3 | 0 | 0 | 0 |

**Cross-layer cycles detected** `[Fact]`:

1. **Models → Services**: `Models/Order` imports `Services/Order/Activity/OrderActivityServiceInterface`, `Services/Order/OrderAttributesServiceInterface`, `Services/Order/Export/OrderReportExporterServiceInterface`, `Http/Traits/ResponseTrait` (via DTO traits) — persistence entities depend on application services.
2. **Repositories → Processor**: filter processors live inside `Repositories/Order/Filter/*` and reach into order semantics.
3. **Services ↔ Services internal churn (31 edges)** — order services call each other and each other's interfaces (`OrderFindWithCommentService`, `OrderCounterService`, `Checker/*`, `Activity/*`), forming an order-service web with no clear entry point.
4. **Http → Repositories (46 edges)**: controllers inject repositories directly (legacy controllers) *and* services/managers (V2 controllers) — two access styles for the same data.
5. **DTOs → Integration (3 edges)** and **Integration → Models (4 edges)**: Dooglys DTOs reference Eloquent models (`Seller`, `File`, `Dictionary`) and Eloquent models appear in integration DTOs — anti-corruption layer leaks domain objects outward.
6. **Providers as the dependency registry**: `RouteServiceProvider.php` (147 lines) hard-binds ~120 interfaces; the container graph is therefore invisible to static analysis and each module's boundary is defined by provider wiring rather than by directory or contract package.

**Direction violations to fix during modularization:** domain layer (`Models`, `DTOs`) currently depends on application (`Services`) and interface (`Http`) layers; infrastructure (`Repositories`) depends on orchestration (`Processor`).

---

## 4. Where business logic actually lives (concentration map) + latent defects

LOC by directory (top): `app/Http` 2725 (controllers 1743, requests 548), `app/Repositories` 2713, `app/Services` 2420, `app/Models` 1574, `app/Managers` 1374, `app/DTOs` 556, `app/Processor` 713, `app/Console` 365, `app/Providers` 354, `app/Integration` 498, `app/Helpers` 229, `app/Listeners` 183, `app/Events` 223, `app/Jobs` 157.

### Direct-dependency table (what each logic cluster touches)

| Cluster | LOC | Eloquent | Queue | Cache | External API | Notes |
|---|---|---|---|---|---|---|
| `Services/Order/*` | ~1250 | heavy (`DB` facade via `DBFacadeTrait`, model queries, `OrderItem`, `File`, `Seller`, `ManufacturerDateLimit`) | no | no | no | checker/export/counter/activity logic |
| `Repositories/Order/*` | ~1300 | heavy: raw `LIKE` on `order_attributes` JSON column; `whereMorphedTo`; `whereHas`/withCount; sub-selects for courier stats | no | no | no | mutable builder state |
| `Managers/*` | ~1374 | heavy: `DB::transaction`, `activity()->disableLogging()` | indirectly (events dispatch) | no | no | transaction + event boundaries |
| `Processor/Order/*` | ~713 | via repositories/managers | via events | no | no | creation/update orchestration |
| `Http/Controllers/Api/Order*` | ~1000 | indirect | indirectly | no | indirectly (reports) | V2 thin controllers |
| `Services/Dooglys/*` + `Integration/Dooglys/*` | ~560 | writes `Dictionary`, `Seller`, `File` | yes (jobs) | no | **HTTP** (Dooglys API, TLS verification disabled) | import logic |
| `Services/VK/*` | ~70 | `UserVkToken` | via listener | no | **HTTP** (VK OAuth) | broken (§4.13) |
| `Http/Middleware/UserActivity` | 77 | none | no | **Cache** `Cache::put(key, value)` **with no TTL** (`UserActivity.php:37`) | no | online-user cache; flush command compensates |
| `Http/Middleware/SanctumPermissions` | 62 | via auth | no | no | no | reflection over `#[Permission]` |
| `Services/Profile/*` | ~120 | none | no | no | `jenssegers/agent` UA parsing | returns `?ProfileInterface` but `ProfileController` dereferences without null guard |
| `Listeners/*` | 183 | `NotificationRepository`, `UserRepository::findAllBy([])` | yes | no | Pusher via broadcaster | fan-out over all users |
| `Jobs/*` | 157 | model re-fetch via `SerializesModels` | yes | no | Dooglys | `release()` re-queue pattern |
| `Console/Commands/*` | 365 | reads `Setting` rows | yes | Cache flush | Dooglys | timers from DB settings |
| `Services/Order/Export/*` | ~400 | `OrderRepository::findAllBy(criteria, [], '', '', 100000)` + `toArray()` | no | no | Excel (Maatwebsite), DomPDF | loads full result set in memory |

### Latent defects found while reading (pre-existing; not migration-caused)

| # | Finding | Evidence | Class |
|---|---|---|---|
| D1 | Undefined variable `$requst` used in `if (!isset($requst['inspector_id']))` — `isset()` on an undefined variable is false, so **inspector assignment never runs** on order creation | `app/Processor/Order/OrderCreatorProcessor.php:48`, `app/Processor/Order/OrderDraftCreatorProcessor.php:37` | bug |
| D2 | Undefined `$requestData` in `OrderFindWithCommentService::orderWithComments(...)` (parameters are `$requestParams`/`$validated`) → sort/limit/offset silently null | `app/Services/Order/OrderFindWithCommentService.php:44-46` | bug |
| D3 | Same typo pattern: `DeliveriesByUserController` builds filter from undefined `$requestData`; its `filter(array: [])` call passes named `array:` against a positional parameter | `app/Http/Controllers/Api/Delivery/DeliveriesByUserController.php:31,36` | bug |
| D4 | `/import/dooglys/menu` dispatches `DooglysSalePointImportJob` (its own menu dispatch is commented out) → menu import endpoint performs sale-point import | `app/Http/Controllers/Api/Import/Dooglys/ImportDooglysMenuController.php` | bug |
| D5 | VK interface/client mismatch: `VkService` calls `getCodeUrl()` / `retrieveAccessToken()`, but `VkApiClient` implements `codeUrl()` / `accessToken()` → runtime fatal whenever VK routes were enabled | `app/Services/VK/VkService.php:22,28` vs `app/Services/VK/VkApiClient.php:33,46` | bug (currently masked by commented routes) |
| D6 | Dooglys client sets `RequestOptions::VERIFY = false` (TLS verification off) and reads `$e->getResponse()` on `ConnectException`, where `getResponse()` is null | `app/Integration/Dooglys/DooglysApiClient.php:60,74` | security + bug |
| D7 | `Cache::put()` without TTL for the online-user set; correctness depends on a scheduled flush every 15 min | `app/Http/Middleware/UserActivity.php:37`, `app/Console/Kernel.php:24` | design |
| D8 | `ProfileController` dereferences `ProfileService::profile()` result, which is typed `?ProfileInterface` | `app/Http/Controllers/Api/Profile/ProfileController.php` | nullability |
| D9 | Read-notification cleanup compares `created_at + SettingTypeEnum::NOTIFICATION__READ_TIMEOUT->value` but loads the **`NOTIFICATION__UNREAD_TIMEOUT`** setting row | `app/Console/Commands/Notification/ClearIrrelevantReadNotificationsCommand.php` | bug |
| D10 | Order-number generation = `count(*) + month`; collisions across months/duplicates are possible; `findLastOrderByOrderNumber` matches the number with a `LIKE` prefix on the JSON `order_attributes` column | `app/Services/Order/NumberGenerator/OrderNumberGeneratorService.php`, `app/Repositories/Order/OrderRepository.php` (findLastOrderByOrderNumber, findLastOrderByOrderDate) | design/business |
| D11 | `OrderRepository::findLastOrderByOrderDate` filters the JSON attribute with `LIKE '%{$date}%'` | `app/Repositories/Order/OrderRepository.php` | fragile |
| D12 | Event fan-out: every order event creates a DB notification row for **every user** and a broadcast for every user; token-revocation event iterates **all users × all tokens** in chunks | `app/Listeners/OrderEntityEventListener.php`, `app/Listeners/UserRevokeAllTokensDueToPermissionsUpdate.php` | scalability |
| D13 | Events are dispatched **inside** `DB::transaction`, and listeners are queued with `SerializesModels` → queued listeners can run before commit and re-fetch models post-transaction | `app/Managers/OrderCompositeManager.php`, `app/Processor/Order/OrderCreatorProcessor.php` | design |
| D14 | Report export loads the entire filtered set (`limit = 100000`, `''` sort) and expands `toArray()` in memory | `app/Services/Order/Export/{OrderReportExporterService,AcceptedOrdersReportService}.php` | memory |
| D15 | `OrderReportController::collection()` swallows the exception and returns **HTTP 200** with `success: false` | `app/Http/Controllers/Api/Order/Report/OrderReportController.php` | contract |
| D16 | Permission drift: seeder defines `*.delete`, `files.*`, `notifications.view`, `users_report.view`, but destructive endpoints guard with `*.edit` and upload/notification endpoints check **nothing**; `vk.integration.edit` is checked in code but **not in the seeder** | `database/seeders/PermissionsTableSeeder.php` vs controllers | security/design |
| D17 | Sanctum tokens are created with device name + abilities and **never rotated/expired**; logout revokes only the current device (`AuthController::logout($id)`) | `app/Services/Auth/AuthUserService.php`, `routes/api.php:101` | security |
| D18 | PHP 8.4/8.5 **implicit-nullable parameter deprecations** (3 declarations): `string $message = null`, `int $total = null` | `app/Http/Responses/ApiResponse.php:18,35`, `app/Http/Responses/ApiResponseInterface.php:22` | deprecation |
| D19 | Duplicate command registration: `user:online-cache-flusher` declared in both `Console/Kernel.php:24` and `routes/console.php:7` | [Fact] | hygiene |
| D20 | Dead code: `CommentsServiceProvider`, `Preparer/Order/OrderAttributesPreparer`, `Preparer/Order/OrderItemAttributesPreparer`, `Factory/RequestBuilderFactory` + `RequestBuilder*` interfaces, `Repositories/Interfaces/{ResetBuilderInterface,ApplyJoinInterface}`, `Models/Traits/CanComment`, `Attributes/Permission` (used only via middleware), legacy `OrderController`/`NotificationController` (unrouted), `Services/Order/OrderItemService`+interface, `DTOs/Order/{OrderItemDictionaryDTO,OrderItemWithDictionaryDTO,OrderDTO}`(partially unused), `Enums/Order/Status/OrderStatus` (unused — statuses are raw strings), `Enums/Order/Type/OrderType` (used only in `OrderCreatorService`) | unreferenced by live route paths | cleanup |
| D21 | TaskBoard module is fully implemented but its sub-routes are commented out (`routes/api.php:289-334`); `TaskController::destroy` uses a **positional** argument where `TaskController::filter(array: [])` is named-parameter; board/group/task controllers duplicate dictionary logic | `routes/api.php`, `app/Http/Controllers/Api/Board/Task/TaskController.php` | hygiene |
| D22 | Status transitions are **not modelled**: statuses are string constants (`draft`, `on_sale`, `in_production`, `production_completed`, `in_delivery`, `delivered`, `payment_confirmed`, `overdue`, `stop`), transitions only happen implicitly via update/accept/courier assignment; `overdue` is produced only by the disabled scheduler | `app/Repositories/Order/OrderRepository.php`, `Enums/Order/Status/*` | business gap |

---

## 5. External integrations and infrastructure dependencies

### 5.1 Dooglys (menu + sale-point import)

| Aspect | State | Evidence |
|---|---|---|
| Transport | Guzzle 7 client, `baseUrl` from `config('dooglys.base_uri')`, `Accept: application/json` | `app/Integration/Dooglys/DooglysApiClient.php:44-60` |
| TLS verification | **disabled** (`RequestOptions::VERIFY = false`) | `DooglysApiClient.php:60` |
| Endpoints used | `GET menu/{id}/items`, `GET salepoints`, `GET salepoints/{id}` | `Services/Dooglys/*`, `Repositories/Dictionary` writes |
| Env/config mismatch | `config/dooglys.php` reads `DOOGLYS_API_URL`, `DOOGLYS_API_TOKEN`; `.env` + `.env.example` declare **`DOOGLYS_BASE_URI`** / `DOOGLYS_API_TOKEN` | `config/dooglys.php`, `.env.example:61-62` → `[Fact]` config value falls back to the hard-coded `http://dev.dooglys.ru` |
| Data mapping | menu items → `Dictionary` rows (product titles), salepoints → `Seller` rows, image download → `File` rows on the `public` disk | `Services/Dooglys/Import*Service`, `Jobs/Dooglys*ImportJob` |
| Queue | both jobs on connection `dooglys_import` (`config/queue.php:57`, `.env.example:63`) | [Fact] |
| Failure handling | `ConnectException` branch calls `$e->getResponse()->getStatusCode()` → null-pointer on connection failure | `DooglysApiClient.php:74` |
| Idempotency | import overwrites/creates rows without transaction-level guard per record | `[Hypothesis]` needs confirmation |

### 5.2 VK integration

Registered providers (`VKServiceProvider`) and event listener `VkUserTokenSaved`, models `UserVkToken`/`VkUser`, but **all VK routes are commented out** (`routes/api.php:82-89`) and the service/client method names mismatch (D5). `config/vk.php` holds app id / client secret / redirect from env. Status: **[Fact] dormant + broken**. Whether it must be revived is an owner question (§9).

### 5.3 Real-time: Pusher + `beyondcode/laravel-websockets`

- `config/broadcasting.php`: default `pusher` (key/secret/app-id/cluster from env), plus `redis`, `log`, `null` drivers.
- `config/websockets.php` present: `apps` from env, port 2000, path `/websocket`, TLS off by default, `DashboardServiceProvider`, `PredisClient` client, history store size 100.
- Supervisor runs `php artisan websockets:serve` (`docker/php/supervisor/laravel-websockets.conf`).
- **Upstream status:** `beyondcode/laravel-websockets` is marked **abandoned** on Packagist (`abandoned: true`, replacement declared), and its dev-master constraints target the Laravel 9 generation → it will not satisfy Laravel 12/13. `[External]`
- `.env.example` sets `BROADCAST_DRIVER=log`, so in the checked-in environment broadcasting is inert; production presumably overrides it (`[Hypothesis]`).

### 5.4 Files / storage

- Upload path: `UploadController` → `UploadManager` → `Storage::disk('public')->storeAs('uploads', filename)`; `File` rows store `disk`, `filename`, `mime`; URLs are produced by `File::url()` → `Storage::url()` on the **default** disk (`local`, which has **no `url` configured** in `config/filesystems.php`) while uploads live on `public` → URL generation depends on `FILESYSTEM_DISK` env value; nginx serves `/storage/` (`docker/nginx/nginx.conf`, latest commits).
- Dooglys menu images are downloaded and stored as `File` rows (`public` disk).
- PDF export fonts live in `storage/fonts`; DomPDF config `config/dompdf.php` (205 lines).

### 5.5 Database / Docker / scheduling

| Aspect | Value | Evidence |
|---|---|---|
| DB | MySQL container `image: mysql:5` | `docker-compose.yml:69` |
| DB config | mysql host `database`, utf8mb4, `strict => true`, no explicit `modes` | `config/database.php` |
| Custom my.cnf mount | mounts `docker/mysql/mysql.cnf` — **that file does not exist**; the tracked file is `docker/mysql/mysql.conf` | `docker-compose.yml:78` vs `git ls-files docker/mysql` |
| PHP image | `php:8.2-fpm-alpine`, extensions: `mysqli, pdo_mysql, mbstring, xml, gd, exif, zip, intl, xsl, sockets` — **no `redis`, no `amqp`, no `pcntl`** | `docker/php/Dockerfile:19-29` |
| Compose services | `php:8001`, `nginx:8000`, `mysql:3306`; `depends_on` without condition keys (Compose file format 3.7) | `docker-compose.yml` |
| Process supervision | nginx, `websockets:serve`, `queue:work --tries=3`, `cron` for laravel-scheduler | `docker/php/supervisor/*`, `docker/php/cron/*` |
| Timezone drift | container `date.timezone = UTC` (`docker/php/php.ini`) vs `config/app.php` timezone `Europe/Moscow`; app locale `ru`, fallback `en` | [Fact] |
| Cache driver (checked-in env) | `CACHE_DRIVER=file`, `CACHE_PREFIX=crm_back` (underscore) while Laravel's Redis prefix convention is hyphenated (see §8.2) | `.env.example:48-49` |
| Horizon | `config/horizon.php` exists (supervisor queue + websockets connection blocks) but **`laravel/horizon` is not a dependency** → dead config | `config/horizon.php`, composer.json |
| CI | no GitHub Actions/gitlab CI; `.styleci.yml` (StyleCI, preset laravel, version 10) only; dependabot commits present in history | `.styleci.yml`, git log |
| Secrets | `.env` is tracked in git; contains `APP_KEY` | git ls-files |

---

## 6. Tests and verification methods

| Item | State |
|---|---|
| `tests/Feature`, `tests/Unit` | **absent** — `git ls-files tests/` returns nothing; `phpunit.xml` suites point at non-existent `tests/Feature` and `tests/Unit` | 
| Untracked leftovers | `tests/App/Http/Controllers/Api/Order/` — three files, **not valid PHPUnit classes** (they are copied request payloads: `CreateOrderRequestTest.php` 163 lines, `CreateOrderDraftRequestTest.php` 142 lines, `UpdateOrderRequestTest.php` 138 lines) |
| Factories | `database/factories/UserFactory.php` only (legacy `Factory` base class) |
| Seeders | 15 seeders (users, roles, permissions + sections, sellers, manufacturers, dictionaries, boards/groups, settings, user-VK) — these are the **de-facto data fixtures** |
| `phpunit.xml` | suites Feature+Unit, `--testdox`, coverage white-list over `app/*` dirs, `.env.testing` overrides | 
| Pest | not installed |
| Static analysis | none (no PHPStan/Larastan, no psalm config); `roave/security-advisories` is the only automated guard |
| Runtime verification available today | not possible on this host: `vendor/` absent, composer not installed |

**Conclusion:** the migration has **no automated safety net**. Verification must be created before the framework bump. Recommended baseline (see §11 Stage 0):

1. `composer install` in the PHP container; `php artisan route:list --json > baseline/routes.json` (contract snapshot).
2. `php artisan schema:dump` (+ keep migrations) for DB baseline; `php artisan config:clear` behaviour check on `.env` variants.
3. Characterization tests for the 106 endpoints: golden response bodies for the top 25 flows (order create/update/accept/courier/comment/draft, dictionary lists, permission denial 403, token abilities).
4. Domain-rule tests for the reconstructed rules in §7 (date limits, status promotion, price scaling, timers) — these are the rules most likely to silently change.
5. Queue/cache integration tests once Redis exists (job retry, unique jobs, overlapping jobs, cache serialization).
6. PHPStan/Larastan level ramp-up + Pint replacing StyleCI (StyleCI's public service is being wound down; `[Hypothesis]` — owner decision).
7. Manual smoke script for the order lifecycle + Dooglys import against a staging Dooglys environment.

---

## 7. Business rules reconstructed from code (all `[Hypothesis]` unless marked `[Fact]`)

| Rule ID | Rule as implemented | Source |
|---|---|---|
| R1 | Order creation requires: seller, inspector, manufacturer, creation date; **inspector assignment is currently never applied** (D1) | `OrderCreatorProcessor`, `Requests/Order/CreateOrderRequest` |
| R2 | Manufacturer date limits: a manufacturer may be blocked for a date window; `full` blocks the whole window, `partial` blocks from `limit_start` to `limit_end`; order creation and update are rejected if the order date falls inside a limit; the limit may be tied to a specific order (`order_id`) | `Services/Order/Checker/OrderCreationRestrictionByManufacturerChecker`, `Requests/Order/*`, `Requests/ManufacturerDateLimit/*` |
| R3 | Draft orders are a separate entity with their own status `draft`, own numbering (`draft_number`), and are purged daily when older than setting `order_draft_deletion_timeout` | `OrderDraftCreatorProcessor`, `ClearDeletedOrderDraftsCommand` |
| R4 | Order number is derived from row count + month component (`OrderNumberGeneratorService`); draft number likewise | `Services/Order/NumberGenerator/*` |
| R5 | Money is stored as **kopecks/cents**: `OrderItem.price`, `total_price`, `cost` are integers scaled by 100 (`OrderItemDTO::getFinalPrice()` returns `intdiv(price*quantity, 100)`); Excel/PDF reports divide by 100 | `DTOs/Order/Item/V2/*`, `Services/Order/Export/*` |
| R6 | Item final price = unit price × quantity (+ decoration where applicable); decoration types: `none`, `cut`, `pack` | `OrderItemDTO`, `Enums/Order/Product/Decoration` |
| R7 | Status vocabulary (9 statuses) with promotion driven by explicit endpoint actions: product `in_production` → `production_completed` (`RestoreOrderProductController`), courier assignment → `in_delivery` (`OrderDeliverySetCourierService`), accepted report counts `payment_confirmed`/`delivered`; `overdue` only via disabled scheduler | `Enums/Order/Status/*`, `Controllers/Api/Order/Product/RestoreOrderProductController.php`, `Services/Order/Delivery/OrderDeliverySetCourierService.php` |
| R8 | Delivery stats: per-user delivered orders + delivered order amount computed with SQL sub-selects (`UserDeliveriesRepository`) | `Repositories/Order/UserDeliveriesRepository.php` |
| R9 | Notifications: every order/notification event → one DB row per user, marked `unread`; unread purged after `notification_unread_timeout` days; read purged after `notification_read_timeout` days (setting lookup mismatch, D9); overdue notification re-queued every `notification_overdue_timeout` minutes (disabled) | `Listeners/*`, `Console/Commands/Notification/*`, `Enums/Setting/SettingTypeEnum` |
| R10 | Token abilities mirror role permissions at token creation; changing a user's role or deleting a user revokes **everyone's** tokens | `AuthUserService:46-53`, `UserRevokeAllTokensDueToPermissionsUpdate` |
| R11 | System settings are key/value rows typed by enum: order draft purge timeout, notification timeouts, replacement-courier id, courier-order pick-up timeout, notification-overdue timeout | `Enums/Setting/SettingTypeEnum`, `Controllers/Api/Setting/*` |
| R12 | Seller list comes from Dooglys sale points; product titles come from Dooglys menus as `Dictionary` rows | `Services/Dooglys/*` |
| R13 | Orders are soft-deleted; deleted drafts purged; comments are polymorphic and soft-deleted | `Models/Order`, `Models/Traits/HasComments` |
| R14 | Task board: boards → groups → tasks with content/attachments; permissions `boards.*`, `boards.group.*`; sub-routes disabled | `Controllers/Api/Board/*`, `routes/api.php:272-334` |
| R15 | Reports: order report (Excel, all filtered orders), accepted-orders report (Excel + PDF), user sales report (Excel), order counters (`OrderCounterService` counts per status) | `Services/Order/Export/*`, `Controllers/Api/Order/Report/*` |

These 15 rules are the **behavioural contract** the new system must reproduce; each needs owner confirmation (see §9).

---

## 8. Migration risks (Laravel 10.50.2 → 13.x, PHP 8.4 → 8.5)

### 8.1 Dependency compatibility matrix

`[External]` = verified from Packagist metadata / official upgrade guides on 2026-08-14.

| Package | Installed | Constraint needed for Laravel 13 | Verdict | Action |
|---|---|---|---|---|
| `laravel/framework` | 10.50.2 | Laravel 13.42.0 requires PHP `^8.3` (12.x requires `^8.2`) | jump 10→11→12→13 | staged, one major at a time |
| PHP platform | runtime 8.4.22; container 8.2 | Laravel 13 `^8.3`; target PHP 8.5 | container must move to 8.5; app constraint `^8.1` must become `^8.3`/`^8.4` | change Dockerfile + composer |
| `phpunit/phpunit` | 9.6.34 (exact-ish `^9.5.10`) | Laravel 13 upgrade guide: update to `^12.0`; phpunit 12/13 require PHP ≥ 8.2/8.4 | incompatible | rewrite test layer anyway (no tests exist) |
| `laravel/sanctum` | 3.3.3 (`^3.2`) | Laravel 11 **dropped Sanctum 3.x**; needs `^4.0`, migrations must be **published** into the app | incompatible at Stage 2 | publish Sanctum migrations, re-test token abilities |
| `doctrine/dbal` | 3.10.5 | Laravel 11 no longer depends on DBAL → removable | removable | drop, but keep column-modifier fixes (see 8.3) |
| `maatwebsite/excel` | **3.1.67 pinned exactly** | 3.1.70 supports Illuminate `^10|^11|^12|^13` (PHP ≥ 7.0); 4.0.6 requires PHP `^8.3` + Illuminate `^12|^13` | pin blocks upgrade | unpin → 3.1.70 for Stage 3, evaluate 4.x for Stage 4 |
| `beyondcode/laravel-websockets` | 1.14.1 | upstream **abandoned**; dev-master targets Illuminate `^9` | incompatible with 12/13 | replace (§11 Stage 5) |
| `pusher/pusher-php-server` | 7.2.7 | 7.3.0: PHP `^7.3|^8.0`, `guzzle ^7.8.2|^8` | compatible | bump to ^7.3 |
| `spatie/laravel-activitylog` | 4.12.1 | latest 5.x requires PHP `^8.4` + Illuminate `^12|^13` | v4 line likely insufficient for 13 | bump to 5.x at Stage 3/4; re-check activity schema |
| `barryvdh/laravel-dompdf` | 2.2.0 | not re-verified in this audit | **unknown** | verify before Stage 4 |
| `guzzlehttp/guzzle` | 7.10.0 | Laravel 13 accepts `^7.8.2|^8` | compatible | optional bump to 8 |
| `nesbot/carbon` | 2.73.0 | Laravel 12 **removed Carbon 2 support** (Carbon 3 required) | incompatible at Stage 3 | migrate to Carbon 3; `diffIn*` semantics change (float + negative) |
| `jenssegers/agent` | 2.6.4 | framework-agnostic, but package age not verified | **unknown** | verify; consider replacement (no framework coupling) |
| `fruitcake/laravel-cors` | 3.0.5 | superseded by framework `HandleCors` in Laravel 11+; package itself deprecated lineage | unused in this app | remove middleware/package, use framework CORS |
| `laravel/tinker` | 2.10.2 | Laravel 13 guide expects `^2.10` | compatible | keep |
| `psr/http-message` | 1.1 (dev, pinned) | Laravel accepts `^1.0|^2.0`; Excel/pusher may pull 2.x | pin may block resolution | remove pin |
| `symfony/*` | 6.4.x | Laravel 13 pulls symfony 7.4/8.0 (console 7.4.4, process 7.4.4, translation 8.0.10, rstring 7.4.4) | transitive only | let Composer resolve; check `Console\Command` usage |
| `roave/security-advisories` | dev-latest | may block resolution on old transitive advisories | friction | keep, expect resolution conflicts during bumps |
| `laravel/horizon` | **absent** (config exists) | needed if Redis queue monitoring is wanted | decision | owner question Q7 |
| Redis client (`predis` or ext-redis) | **absent** | Redis cache/queue requires ext-redis (phpredis is the default client in `config/database.php:122`) | **blocking gap** | add ext-redis to Dockerfile (+ composer client if predis) |
| RabbitMQ/AMQP | **absent** | no Laravel RabbitMQ driver ships in the framework; needs ext-amqp + a bridge package | **blocking gap** | owner decision Q6/Q7 |

### 8.2 Framework breaking changes that touch *this* code

Ordered by likelihood of hitting this codebase (each sourced from the official upgrade guides, `[External]`):

| # | Change | Version | Why it bites here | Severity |
|---|---|---|---|---|
| M1 | **Application structure** (fewer providers/middleware/config; `bootstrap/app.php` middleware object) | 11.x | Laravel 11 explicitly supports the Laravel 10 structure — keep `Kernel.php` + `config/app.php` providers/aliases during Stage 2 and move structure only in the refactor phase | Medium (avoid churn) |
| M2 | **Sanctum 4** (no auto migrations; publish them) | 11.x | Token/ability model migrations must be published; ability list is the app's whole authorization model (§2.2) | **High** |
| M3 | **`->change()` column modifiers must be re-declared** | 11.x | `database/migrations/*order_items*` use `->change()` for `label`, `decoration`, `comment`, `orders.number` etc.; missing modifiers are silently dropped (defaults/comments/nullable lost) | **High** |
| M4 | **`double`/`float` column type semantics** | 11.x | No `float`/`double` in this repo's migrations (`[Fact]`) → not applicable, but verify new DDL | Low |
| M5 | **Doctrine DBAL removal** | 11.x | drop `doctrine/dbal`; nothing else in the app uses DBAL directly | Low |
| M6 | **Carbon 3 mandatory** (12.x drops Carbon 2) | 12.x | date math in commands (`created_at->diffInSeconds`-style comparisons), DTO date formatting (`DTOs/Order/OrderDTO.php` formats `order_date` with Carbon), report date handling; `diffIn*` now returns float/negative | **Medium-High** |
| M7 | **Cache `serializable_classes` default `false`** | 13.x | `UserActivity` middleware stores arrays of scalars (`[Fact]` safe), but any object cached would now fail; check future caching of DTOs/models | Medium |
| M8 | **Cache key prefix hyphenated convention** | 13.x | `CACHE_PREFIX=crm_back` (underscore) and custom keys `user_online`, `order_report_filter:*` — prefix/format changes can invalidate cached keys across deploys | Medium |
| M9 | **Model-collection serialization restores eager-loaded relations** | 13.x | Repositories return Eloquent collections and controllers call `toArray()` with eager loads (`items.files` with a limited column selection) → serialized shapes may gain/lose keys | Medium |
| M10 | **Polymorphic pivot table naming** | 13.x | `comments` (`morphs`) and `notifications` (`notifiable`) use polymorphism; `whereMorphedTo` in `NotificationRepository`; pivot naming change affects morph pivots — verify this app's morph tables | Medium |
| M11 | **MySQL `DELETE` with `JOIN`/`ORDER BY`/`LIMIT`** | 13.x | repositories delete via model/`where` only (`[Fact]`); but `AbstractRepository` builder reuse + future joined deletes need care | Low |
| M12 | **`Container::call()` defaults are nullable** | 13.x | method injection is used heavily in V2 controllers; parameter default semantics could change resolution of scalar route params (`int $id`) | Medium |
| M13 | **`Dispatcher::dispatchAfterResponse` contract / event payload changes** (`JobAttempted`, `QueueBusy`, `BatchBusy`, `Manager::extend` binding) | 13.x | no custom queue events today (`[Fact]`) → low, but monitoring additions must use new names | Low |
| M14 | **Session/config serialization flags** | 13.x | API-only app, session unused except Sanctum CSRF path in CORS config | Low |
| M15 | **Pagination bootstrap view names** | 13.x | no framework pagination rendering (`[Fact]` limit/offset handled manually) | Low |
| M16 | **Per-second rate limiting + `GlobalLimit` seconds** | 11.x | no rate limiters today (`[Fact]`) | Low |
| M17 | **`sync` queue respects `after_commit`** | 11.x | default connection is `sync` (`config/queue.php:19`) while events are dispatched inside transactions → behaviour of inline listeners changes | **Medium** |
| M18 | **`UserProvider` contract gains `rehashPasswordIfRequired`; password auto-rehash on login** | 11.x | `User` model uses standard `password`; rehash happens silently on login → token/ability cache assumptions need re-verification | Low-Medium |
| M19 | **`casts()` method on base model** | 11.x | models define `$casts` arrays (no `casts()` relation) → safe | Low |
| M20 | **PHP 8.4/8.5 deprecations** | platform | 3 implicit-nullable declarations (D18) emit `E_DEPRECATED`; alpine/FPM log noise + strict error handling risk | Medium |
| M21 | **PHP 8.5 + `.styleci.yml`** | tooling | StyleCI config pins `version: 10`; formatter service status needs a decision (Pint) | Low |

### 8.3 Concrete blockers (must be resolved before the upgrade can even run)

| B | Blocker | Evidence |
|---|---|---|
| B1 | `vendor/` absent, composer absent on host → nothing can be built/tested locally | repo listing |
| B2 | **Redis client missing**: `CACHE_DRIVER`/`QUEUE_CONNECTION` cannot use Redis without ext-redis (config defaults to `phpredis`), yet Redis cache is a migration goal | `config/database.php:122`, `docker/php/Dockerfile:19-29` |
| B3 | **RabbitMQ has no first-party Laravel driver**; no AMQP package or ext-amqp present → driver choice is a design decision, not a config switch | composer.json |
| B4 | `beyondcode/laravel-websockets` abandoned → supervisor process (`docker/php/supervisor/laravel-websockets.conf`) and `config/websockets.php` become orphaned at Laravel ≥ 12 | [External] + repo |
| B5 | `maatwebsite/excel` pinned at exact `3.1.67` → cannot satisfy Illuminate `^13` without unpinned bump | `composer.json:17` |
| B6 | Sanctum 3.x cannot coexist with Laravel 11+; token abilities are the authorization model, so Stage 2 changes auth behaviour | `composer.json:15` + [External] |
| B7 | `tests/` empty → no regression evidence for any stage | git ls-files |
| B8 | `docker/mysql/mysql.cnf` referenced by compose but not present; `mysql:5` (EOL major version) vs MySQL 8 required for modern Laravel/utf8mb4 assumptions | `docker-compose.yml:69,78` |
| B9 | `.env` tracked in git with `APP_KEY`; env var names drift (`DOOGLYS_BASE_URI` vs `DOOGLYS_API_URL`) → deploy/secret hygiene must be settled before Docker/Compose rework | git ls-files, config diff |
| B10 | Compose file format `3.7` with unconditioned `depends_on` (includes Redis healthcheck in the *unused* websockets compose block) → orchestration rework needed for Redis/RabbitMQ services | `docker-compose.yml:29-43` |
| B11 | Container PHP 8.2 vs target PHP 8.5 and framework constraint `^8.1` → both image and composer constraint must move; Laravel 13 requires PHP ≥ 8.3 | `docker/php/Dockerfile:1` |

### 8.4 Behavioural risks that survive the upgrade (design debt the new architecture must absorb)

- **Fan-out writes** (D12) become RabbitMQ publish storms if events are externalized: one order event → N users × (DB row + broadcast + job).
- **Events inside transactions** (D13) + `SerializesModels` jobs: with an external broker, publish-before-commit and post-commit re-fetch semantics must be redesigned (outbox pattern recommended).
- **Recurring notification by `release()`** (job with `tries = 1`) depends on queue-driver-specific release semantics; a RabbitMQ bridge may not honour `release()` the same way → replace with scheduled scan.
- **Unique/overlapping job guarantees**: any future `ShouldBeUnique`/`WithoutOverlapping` needs a Redis/cache lock; today's `sync` driver bypasses them.
- **Mutable shared state**: static `AbstractApiController::$response` (`AppServiceProvider::boot()`), repository-held `$builder`, and `activity()->disableLogging()` global toggles are cross-request state that will behave differently when workers are long-lived (Horizon/RabbitMQ consumers) or when modules are isolated.
- **Memory-heavy exports** (D14) need streaming chunked exports (Excel chunk layer + cursor) before Redis/RabbitMQ workers run in bounded memory.
- **Permission drift** (D16) means authorization cannot be "cleaned" silently — it changes what users can do; owner decision required (Q4).

### 8.5 Risk register summary (likelihood × impact)

| Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|
| Auth/token ability regressions (Sanctum 4) | High | High | publish migrations, contract tests for 403/200 per endpoint, token ability snapshot tests |
| Silent column attribute loss via `->change()` | High | High | audit every `->change()`, re-declare modifiers, or squash with `schema:dump` |
| Date/time semantics after Carbon 3 | High | Medium | date-focused characterization tests; centralise date handling in a value object |
| Excel export API change | High | Medium | unpin, keep exports behind an export service with golden files |
| Real-time layer unavailable at Laravel 12/13 | High | Medium | choose replacement early; decouple broadcaster from domain events |
| Redis missing at deploy | High | High | ext-redis in image + Redis service + cache/queue integration tests |
| RabbitMQ driver choice churn | Medium | High | spike first, then decide (bridge vs Horizon+Redis vs custom consumer) |
| Queue semantics change (`sync` after_commit, retries, unique locks) | High | Medium | integration tests; move event publishing out of transactions |
| Response shape drift from serialization changes | Medium | Medium | golden response snapshots for 106 endpoints |
| Deprecated-notice noise on PHP 8.5 | High | Low | fix 3 implicit nullables; enable strict deprecation reporting in CI |
| Latent bugs surfacing during upgrade (D1-D9) | Medium | Medium | fix in a separate pre-migration commit, or pin behaviour with tests first |
| Data migration (MySQL 5 → 8) | Medium | High | dump/reload + charset/collation verification |
| Dead-code deletion breaking hidden callers | Medium | Low | grep-verified deletion (this audit lists candidates) |

---

## 9. Questions for the product owner

**Blocking** (answers determine the migration architecture — cannot proceed without them):

| Q | Question | Why it blocks |
|---|---|---|
| Q1 | Is the target **PHP 8.5 exactly**, and is Laravel **13.42** (latest) acceptable, or do you want a pinned LTS-ish target? Current container is PHP 8.2; Laravel 13 requires ≥ 8.3. | image + composer constraints + package matrix |
| Q2 | Which **MySQL version** will production run (compose uses `mysql:5`)? Is a data migration to MySQL 8 in scope, with a maintenance window? | schema, collation, `strict` modes, `->change()` fixes |
| Q3 | **RabbitMQ driver decision**: Laravel has no first-party AMQP driver. Options: (a) AMQP bridge package (spatie/laravel-rabbitmq-style), (b) Horizon + Redis queues, (c) custom consumer worker. Which one, and does Horizon get adopted (its config file already exists but the package is absent)? | Stage 5 design, ext-amqp/ext-redis in image |
| Q4 | **Authorization model**: permissions exist in the DB seeder as `*.view/edit/delete`, but destructive endpoints guard with `*.edit`, upload and notification endpoints check nothing, and `vk.integration.edit` is not seeded. Should the upgrade **enforce** the missing checks (behaviour change for users) or preserve current behaviour verbatim? | security vs behaviour parity |
| Q5 | **Token lifecycle**: tokens today never expire and role changes revoke *everyone's* tokens. Keep that blast radius, or scope revocation to the affected user? | Sanctum 4 migration + listener redesign |
| Q6 | **Notification audience**: every order event currently notifies **all users**. Is that the intended business rule (global broadcast) or a bug to fix (role/targeted recipients)? | fan-out redesign, RabbitMQ message volume |
| Q7 | **Real-time replacement**: `beyondcode/laravel-websockets` is abandoned. Keep Pusher-only, self-host a replacement (e.g. pusher-compatible server), or drop real-time? Also: is `BROADCAST_DRIVER=log` the real production value? | supervisor processes, config, front-end contract |
| Q8 | **Order-numbering rule** (`count + month`, `LIKE` search on JSON attribute): is this scheme contractual with the front-end/back-office? Any uniqueness guarantee required? | schema (unique index), number generator redesign |
| Q9 | **Money representation**: prices are stored in kopecks (×100) and divided in DTO/reports. Confirm the canonical unit and rounding policy. | domain value objects, migration data checks |
| Q10 | **Status transitions**: the 9 statuses have no state machine in code; `overdue` is produced only by a disabled scheduler. Is there a documented transition matrix (who may move an order to which status, and when)? | Order aggregate design |
| Q11 | **Inspector assignment**: `inspector_id` is validated on create but never stored (D1). Is assignment on creation intended, or is assignment done elsewhere (draft promotion)? | order creation rule |
| Q12 | **Manufacturer date limits**: confirm `full` vs `partial` semantics and whether a limit tied to `order_id` blocks only that order. | validation/checker rules |
| Q13 | **VK integration**: dormant and broken (D5). Revive, delete, or keep dormant? Are VK credentials still valid? | module scope |
| Q14 | **Dooglys contract**: which base URL per environment (`config/dooglys.php` expects `DOOGLYS_API_URL`, env provides `DOOGLYS_BASE_URI`), is TLS verification intentionally disabled, and is the `/import/dooglys/menu` endpoint supposed to import menus (currently imports sale points, D4)? | integration module + env config |
| Q15 | **Task board scope**: sub-routes (products/dictionaries/tasks/attachments) are commented out while controllers/requests/repositories exist. Is TaskBoard in scope for the new modular monolith, and if so, what is its real route surface? | module boundaries |
| Q16 | **Dead code policy**: may we delete unreferenced code (D20 list) and the legacy V1 controllers (`OrderController`, `NotificationController`, legacy dictionary managers) in the same pass, or must they stay reachable? | refactor scope, risk |
| Q17 | **Deployment shape**: is Docker Compose the production mechanism (currently dev-oriented, format 3.7), and who owns secrets (`.env` is committed with `APP_KEY`)? Will secrets move to vault/env injection? | infra deliverables |
| Q18 | **Downtime tolerance**: can the upgrade ship as one release window (big-bang), or must it be incremental with the old API serving concurrently (strangler)? | staging plan |

**Non-blocking** (answers refine details, not the plan):

| Q | Question |
|---|---|
| Q19 | Redis scope: cache only, or cache + queue + broadcast + locks? TTL policy for `user_online` (today: no TTL + scheduled flush)? |
| Q20 | Test framework preference: PHPUnit 12 (upgrade-guide default) or Pest? Target coverage floor for the new module tests? |
| Q21 | CI: no pipeline exists today (only StyleCI + Dependabot). Add GitHub Actions (lint + static analysis + tests) as part of this migration? |
| Q22 | Formatting: replace StyleCI with Pint (StyleCI's hosted service status is uncertain)? |
| Q23 | Static analysis level (PHPStan/Larastan) acceptable as a gate, and at what level initially? |
| Q24 | Timezone policy: container `date.timezone=UTC` vs app `Europe/Moscow` — keep UTC in DB and localize at the edge? |
| Q25 | Locale: `lang/ru` lacks `pagination.php`; keep `ru` default with `en` fallback in the new structure? |
| Q26 | File storage: keep public-disk uploads served by nginx (`/storage/`), or move to S3/private disk with signed URLs? Note `File::url()` uses the default disk today. |
| Q27 | Export volume limits: acceptable row cap for Excel/PDF reports (today: up to 100 000 rows in memory)? |
| Q28 | Activity-log retention: purge policy for Spatie activity rows, and does audit need to survive the module refactor (it currently wraps manager transactions)? |
| Q29 | Multi-instance deployment: will the API run multiple pods/replicas? (Static shared state + repository builder state + `activity()->disableLogging()` are unsafe under concurrency.) |
| Q30 | Front-end contract: is there a spec/OpenAPI for the 106 endpoints, or is the current JSON envelope (`{success, data, total?, message}`) the de-facto contract to freeze? |
| Q31 | Order drafts: is `draft` → real order promotion a supported flow today (draft routes exist; promotion logic unclear)? |
| Q32 | Delivery/courier rules: what does `order.delivery.courier.edit` mean business-wise (self-assignment vs the "replacement courier" setting `courier_id`), and how should overdue pickup behave (scheduler disabled)? |

---

## 10. Preliminary old → new component mapping

Target shape: **modular monolith**, one deployable, modules isolated by namespace + explicit contracts, each module layered `interface → application → domain → infrastructure`. Domain layer must not depend on Eloquent, HTTP, or the queue.

### 10.1 Proposed modules and their incoming components

| New module (bounded context) | New namespace | Absorbs (old paths) | Primary entities / aggregates | Notes |
|---|---|---|---|---|
| **identity** | `Modules\Identity\…` | `Controllers/Api/{Auth,User,Role,Permission,Profile}Controller*`, `Requests/{Auth,User,Role}/*`, `Services/Auth/AuthUserService`, `Services/Profile/*`, `Repositories/{UserRepository,UserRepositoryInterface,RoleRepository,RoleRepositoryInterface,PermissionRepository,PermissionRepositoryInterface}` + `Controllers/Api/User/UserStatusDictionaryController`, `Models/{User,Role,Permission,PermissionSection}`, `DTOs/{UserDTO,UserWithPermissionsDTO,RoleDTO,PermissionDTO,PermissionSectionDTO,ProfileInterface}`, `Manager/{UserManagerInterface,UserManager,RoleManagerInterface,RoleManager,PermissionManagerInterface,PermissionManager}`, `Events/UserRevokeAllTokensEvent`, `Listeners/UserRevokeAllTokensDueToPermissionsUpdate`, `Attributes/Permission`, `Middleware/SanctumPermissions`, `Middleware/UserActivity`, enums `Enums/User/{Status,StatusEnum,StatusInterface}` | User aggregate (tokens/devices), Role→Permission policy | Auth + RBAC. Permission catalogue becomes policy, not token abilities copy (Q5) |
| **orders** | `Modules\Orders\…` | `Controllers/Api/Order/V2/*`, `Controllers/Api/Order/OrderDictionaryController`, `Controllers/Api/OrderCommentController`, `Controllers/Api/Order/OrderActivityController`, `Requests/Order/*`, `Requests/OrderComment/*`, `Repositories/Order/{OrderRepository,OrderRepositoryInterface,AbstractRepository,Filter/*,Sort/*}`, `Services/Order/{OrderService,OrderServiceInterface,OrderCreationRestrictionByManufacturerChecker,OrderAttributesService,OrderFindWithCommentService,OrderActivityService}`, `Processor/Order/{OrderCreatorProcessor,OrderUpdaterProcessor,OrderProcessor,ProcessorInterface,ProcessorExtension,OrderCommentProcessor}`, `Manager/{OrderCompositeManager,OrderCompositeManagerInterface,OrderManagerExtension,OrderManagerInterface,OrderManager}`, `Models/{Order,OrderComment}`, `DTOs/{OrderDTO,OrderDictionaryDTO,OrderAttributesDTO,OrderActivityDTO,CommentDTO}`, `Enums/Order/{Status/*,Type/*}`, `Events/{OrderEntityCreated,OrderEntityUpdated,OrderNotificationEvent,OrderEvent}`, `Jobs/OrderOverdueNotificationJob`, `Console/Commands/Order/{Notification/*,Drafts/*}`, `Preparer/Order/*` (after verification) | Order aggregate (items, contact, delivery, comments, status transitions, numbering) | Core context. Status vocabulary becomes an explicit state machine (Q10) |
| **orders.drafts** | `Modules\Orders\Drafts\…` (or sub-module inside orders) | `Controllers/Api/OrderDraft/V2/*`, `Requests/OrderDraft/*`, `Repositories/OrderDraft/*`, `Services/OrderDraft/*`, `Manager/OrderDraftManager`, `Processor/Order/OrderDraft*`, `Console/Commands/Order/Drafts/*` | DraftOrder aggregate | Keep as separate aggregate; shares number generator |
| **catalog** | `Modules\Catalog\…` | `Controllers/Api/Order/Product/*`, `Requests/Order/Product/*`, `Repositories/Order/{OrderItemRepository,OrderItemDictionaryRepository,OrderItemFilterProcessor}`, `Services/Order/{OrderProductService,OrderProductServiceInterface,OrderProductDictionaryService,OrderProductDictionaryServiceInterface}`, `Models/OrderItem`, `DTOs/Order/Item/*`, `DTOs/{OrderItemDictionaryDTO,OrderItemWithDictionaryDTO}`, `Enums/Order/Product/{ProductTitle,Decoration}`, `Models/Dictionary`, `Repositories/DictionaryRepository`, `Models/Unit`, `Enums/{Unit,UnitEnum,UnitInterface}`, `Models/Contact`, `Enums/Contact/{ContactType,ContactTypeEnum,ContactTypeInterface}` | ProductTitle (dictionary), OrderItem line, Unit, Contact type | Reference data + order lines; Dooglys menu import feeds ProductTitle |
| **delivery** | `Modules\Delivery\…` | `Controllers/Api/Delivery/*`, `Requests/Order/UpdateDeliveryRequest.php`, `Services/Order/Delivery/*`, `Repositories/Order/{OrderDeliveryRepository,UserDeliveriesRepository}`, `Models/OrderDelivery` | Delivery/courier assignment | Depends on orders for status promotion; keep outbound port |
| **counterparties** | `Modules\Counterparties\…` | `Controllers/Api/{SellerController,SellerDictionaryController,ManufacturerController,ManufacturerDictionaryController,DateLimitController,DateLimitDictionaryController}`, `Requests/{Seller/*,Manufacturer/*,ManufacturerDateLimit/*}`, `Repositories/{SellerRepository,ManufacturerRepository,ManufacturerDateLimitRepository,ManufacturerDictionaryRepository}`, `Services/Order/Checker/OrderCreationRestrictionByManufacturerChecker`, `Models/{Seller,Manufacturer,ManufacturerDateLimit}` | Seller, Manufacturer (+ date-limit policy) | Date-limit policy is an order-facing port (Q12) |
| **notifications** | `Modules\Notifications\…` | `Controllers/Api/Notification/*`, `Repositories/NotificationRepository`, `Manager/NotificationManager`, `Notifications/OrderDatabaseNotification`, `Events/NotificationEvent`, `Listeners/{NotificationEventListener,BroadcastEventListener}`, `Jobs/{NotificationEventListener,BroadcastEventListener}`, `Console/Commands/Notification/*`, `Enums/Setting/SettingTypeEnum` (timeout settings) | Notification (per recipient) | Domain events published, recipients resolved by policy (Q6); real-time port (Q7) |
| **taskboard** | `Modules\TaskBoard\…` | `Controllers/Api/Board/*`, `Requests/Board/*`, `Repositories/Board/*`, `Manager/Board/*`, `Models/{Board,BoardGroup,Task,TaskAttachment,TaskContent}`, `DTOs/Board/*`, `DTOs/Task*`, seeders `Board/*` | Board, Group, Task | Scope pending Q15; routes partially commented |
| **files** | `Modules\Files\…` | `Controllers/Api/Upload/*`, `Services/Upload/*`, `Models/File`, `Repositories/FileRepository`, `DTOs/Upload/*` | File | Storage abstraction + URL policy (Q26) |
| **reporting** | `Modules\Reporting\…` | `Controllers/Api/Order/Report/*`, `Controllers/Api/User/{ListUserReportController,ExportUserReportController}`, `Services/Order/Export/*`, `Services/Order/{OrderReportCounterService,AcceptedOrdersReportCounterService,OrderCounterService}`, DTOs feeding reports | Report queries (read-only side) | CQRS-ish: read models, streaming exports (Q27) |
| **audit** | `Modules\Audit\…` | `Controllers/Api/Activity/*`, `Services/Activity/*`, `Services/Order/Activity/*`, `DTOs/Activity/*`, Spatie integration | Activity log | Keep as append-only log; recording moved to application layer (Q28) |
| **settings** | `Modules\Settings\…` | `Controllers/Api/Setting/*`, `Controllers/Api/OrderSetting/*`, `Controllers/Api/Order/OrderSettingDictionaryController`, `Controllers/Api/Order/OrderStatusDictionaryController`, `Repositories/SettingRepository`, `Enums/Setting/*`, `Models/Setting`, `DTOs/SettingDTO` | Settings (business timers + switches) | Config-as-data; consumed by orders/notifications via ports (Q11/Q32) |
| **integrations.dooglys** | `Modules\Integrations\Dooglys\…` | `Integration/Dooglys/*`, `Services/Dooglys/*`, `Jobs/Dooglys*ImportJob`, `Console/Commands/Dooglys/*`, `Manager/{DooglysMenuManager,DooglysSalePointManager}`, `DTOs/{MenuDTO,SalePointDTO,SalePointImportDTO,MenuImportDTO,MenuDictionaryDTO}`, `DTOs/Dooglys/*`, config `dooglys.php` | import processes | Anti-corruption layer: no Eloquent models leaking in (current violation §3.2 #5) |
| **integrations.vk** | `Modules\Integrations\Vk\…` (if kept) | `Services/VK/*`, `Controllers/Api/VK/*` (commented), `Listeners/VkUserTokenSaved`, `Events/VKUserAuthEvent`, `Models/{UserVkToken,VkUser}`, config `vk.php` | VK token | Scope pending Q13 |
| **shared kernel** | `Support\…` | `Http/Responses/*`, `Http/Traits/ResponseTrait`, `Helpers/*` (replace global functions with explicit services), `Exceptions/*`, `DTOs/DataTransferObject` (replace with typed DTOs/value objects), `Repositories/AbstractRepository` (replace with module-local repositories + query services), `Models/Traits/*`, `Providers/RouteServiceProvider` bindings (replace with per-module providers) | — | Keep minimal: HTTP envelope, error handling, cache-key naming, clock/ids. **Avoid a shared "everything" base repository** |
| **infrastructure** | `Infrastructure\…` | `Providers/*` wiring, `Http/Kernel.php` → `bootstrap/app.php` middleware pipeline, queue/cache/broadcast configuration, persistence adapters (Eloquent models moved out of domain), RabbitMQ/Redis adapters | — | All Eloquent models become infrastructure adapters implementing domain interfaces |

### 10.2 Pattern-level mapping (how current patterns translate)

| Current pattern | New representation |
|---|---|
| `AbstractRepository` + filter/sort/limit/offset traits (`app/Repositories/AbstractRepository.php`) | module-local **read repositories / query services** per aggregate; no cross-module base class; pagination as explicit value object |
| Manager (`OrderCompositeManager`) doing `DB::transaction` + `activity()->disableLogging()` + event dispatch | **application service / use case** owning the transaction; audit recorded via a port; domain events collected and published **after commit** (outbox) |
| Processor (`OrderCreatorProcessor`, `ProcessorExtension`, `OrderItemProcessor`) | **use-case handlers** composed explicitly (no static `$attributes` shared state between processors) |
| `ManagerExtension::create()` static dispatch to `OrderModelObserver` | **domain events + explicit event handlers** (drop the static dispatcher + observer indirection) |
| `#[Permission]` attribute + middleware reflection + `isAllowed()` | **authorization policy objects** enforced in the interface layer, one mechanism (Q4) |
| Sanctum token abilities copied from permissions | **permission snapshot issued as token abilities** with explicit lifecycle (Q5) |
| DTOs as array bags (`DataTransferObject`) | typed DTOs / value objects (Money, OrderNumber, DateWindow, Status) |
| Global helpers (`autoload.files`) | explicit service classes injected where needed |
| Providers binding ~120 interfaces in one file | **per-module provider** exposing only its public contracts; cross-module calls via contracts, never via concrete repositories |
| Queued listeners with fan-out | domain event → **outbox → RabbitMQ publisher** → notification worker; recipients resolved by policy |
| `Cache::put(key, value)` scattered keys | central cache-key registry + TTL policy in `Support\Cache` |
| Console commands reading `Setting` rows | settings port + scheduled jobs (scheduler stays in Laravel; RabbitMQ consumers separate process) |
| Excel/PDF exporters | reporting module adapters with **chunked/streaming** export |
| `beyondcode/laravel-websockets` | replacement behind a **RealtimePublisher port** |
| `jenssegers/agent` UA parsing | `Support\UserAgent` adapter (or drop; profile endpoint scope) |

### 10.3 Boundary rules the mapping implies

1. `Modules\X\Domain` may import only PHP + `Support` primitives — **no** Eloquent, HTTP, queue, Redis, Guzzle.
2. Cross-module references go through `Modules\X\Contracts\…` (published contracts), never through models or repositories.
3. Persistence models live in `Modules\X\Infrastructure\Persistence` and implement domain interfaces.
4. HTTP layer lives in `Modules\X\Interface\Http` (one route group per module, one provider per module).
5. No module owns global mutable state (statics, trait-pools) — required for long-lived workers (Q29).
6. Read-side reporting does not import write-side aggregates; it queries its own read models.

---

## 11. Staged migration roadmap (recommended execution order)

**Principle: separate the framework/platform upgrade from the architectural refactor.** Upgrade first on the existing structure (Laravel 11 explicitly keeps Laravel 10 layout working), then modularize on a green baseline. Doing both at once makes regressions unattributable.

| Stage | Goal | Concrete actions | Gate to pass before next stage |
|---|---|---|---|
| **0. Baseline & guardrails** | make the current system measurable | install deps in container; `route:list --json` snapshot; `schema:dump`; golden-response tests for top 25 flows; PHPStan/Larastan baseline; CI pipeline; freeze the JSON envelope contract (Q30) | CI green on Laravel 10 / PHP 8.2 as-is; snapshots committed |
| **1. Pre-migration fixes (behaviour-neutral)** | remove landmines | fix implicit nullables (D18); fix `$requst`/`$requestData` bugs (D1-D3) **only after tests pin intended behaviour** (Q11); replace unused `fruitcake/laravel-cors` with framework `HandleCors`; remove dead code (D20, pending Q16); unify duplicate command registration (D19) | tests prove intended behaviour; owner sign-off on each fix |
| **2. Infra: Redis + runtime** | platform readiness | Docker image → PHP 8.4/8.5, add `ext-redis` (+ `ext-amqp` if Q3 chooses AMQP); Redis service in compose; cache driver → redis with TTL policy; queue → redis; MySQL 5 → 8 data migration (Q2); compose rework (B8/B10); secrets out of git (B9) | cache/queue integration tests pass; deployed dev/staging environment verified |
| **3. Laravel 11** | framework step 1 | `laravel/framework:^11.0`; **Sanctum ^4 + published migrations** (M2); drop `doctrine/dbal`; re-declare all `->change()` modifiers (M3); keep `Kernel.php` + `config/app.php` providers/aliases (M1); verify `sync` after-commit behaviour (M17); phpunit ^10 | CI green; token/ability contract tests green; migration re-run on fresh DB |
| **4. Laravel 12** | framework step 2 | `laravel/framework:^12.0`; **Carbon 3** (M6) + date semantics audit (R7/R9/R11); `phpunit/phpunit:^11`; unpin Excel → 3.1.70; activitylog 4→5; cache-prefix handling (M8); remove `fruitcake` leftovers if still present | CI green; date/timezone tests green; export golden files green |
| **5. Laravel 13** | framework step 3 | `laravel/framework:^13.0`, PHP `^8.3+` (8.5 target), `phpunit:^12`; enable/verify `config/cache.serialization.serializable_classes` (M7); audit serialized `toArray()` shapes (M9) and morph pivots (M10); replace websockets package (Q7); container PHP 8.5; strict deprecation reporting | CI green; response-shape snapshots unchanged or explicitly re-baselined |
| **6. Modularization (strangler)** | architecture | create `Modules/…` per §10; move code module by module (orders first, then identity, counterparties, catalog, notifications, reporting, integrations); contracts + per-module providers; domain layer freed of Eloquent/HTTP; typed DTO/value objects replacing array DTOs; helpers → services | module tests + integration tests per module; old paths deleted only after parity tests pass |
| **7. Eventing & queue on RabbitMQ** | async layer | implement Q3 decision; outbox publishing after commit (fix D13); replace `release()`-based recurring notification with scheduled scan (D/job redesign); recipient policy (Q6); locks for unique/overlapping jobs; consumer processes supervised separately; dead-letter + failed-job handling | load test on order-event fan-out; DLQ/retry behaviour verified |
| **8. Cleanup + hardening** | finish line | delete legacy V1 controllers & dead code (Q16); authorization unified (Q4); token lifecycle (Q5); exports streamed (Q27); audit retention (Q28); CI + static-analysis gate raised; docs/OpenAPI contract published | full regression suite green; deployment verified; rollback path tested |

**Sequencing notes:**
- Stage 2 must precede Stage 3 because Redis is required by Laravel 11+ queue/cache expectations and by Horizon/AMQP later; it also gives a place to run real tests.
- Stage 5's websockets replacement is coupled to Q7 — if replacement is deferred, Laravel 12 becomes the ceiling for the real-time path.
- Stage 6 should not start before Stage 5 is green; otherwise failures are ambiguous (framework vs refactor).
- Stage 7 depends on Q3/Q6 answers; the outbox redesign is required regardless of broker choice.

---

## 12. Evidence appendix (file → what it proves)

| File | Line(s)/symbol | Proves |
|---|---|---|
| `composer.json` | `:8 php ^8.1.0`, `:14 framework ^10.0`, `:15 sanctum ^3.2`, `:17 excel 3.1.67`, `:11 dbal ^3.6`, `:28 phpunit ^9.5.10`, `autoload.files` | declared constraints + global helper autoload |
| `composer.lock` | `laravel/framework 10.50.2`, `sanctum 3.3.3`, `excel 3.1.67`, `dbal 3.10.5`, `phpunit 9.6.34`, `websockets 1.14.1`, `activitylog 4.12.1`, `pusher 7.2.7`, `carbon 2.73`, `symfony 6.4.35`, platform `PHP 8.4.22` | locked versions + host platform |
| `bootstrap/app.php` | 19 lines | Laravel 10 kernel-based bootstrap |
| `config/app.php` | `:139 providers` array, `aliases`, `timezone Europe/Moscow`, `locale ru`, `fallbackLocale en` | explicit provider/alias wiring, locale/timezone |
| `app/Http/Kernel.php` | `:36 CorsHandler`, `:63-64 ForceJsonResponse/UserActivity`, `:85 sanctum.permissions`, `:88+ middlewarePriority` | custom middleware stack + priority trick |
| `app/Providers/RouteServiceProvider.php` | `:23-146` | ~120 interface bindings = container graph |
| `app/Providers/AppServiceProvider.php` | `boot()` | static `AbstractApiController::setResponse()`, validation middleware injection |
| `app/Providers/{OrderServiceProvider,OrderEntityEventServiceProvider,DooglysServiceProvider,VKServiceProvider,CommentsServiceProvider,SanctumPermissionsServiceProvider}` | — | module wiring; `CommentsServiceProvider` unreferenced |
| `routes/api.php` | `:77-341`, `:82-89` (VK commented), `:289-334` (task-board commented), `:114` menu dispatch | 106 active endpoints, disabled route groups |
| `routes/channels.php` | 3 private channels | broadcast channel contract |
| `routes/console.php` | `user:online-cache-flusher` | duplicate command registration |
| `app/Console/Kernel.php` | `:24-28` | scheduled tasks; `:26` commented pusher schedule |
| `app/Repositories/AbstractRepository.php` | traits + mutable `$builder` + `resetBuilder()` | repository pattern shape |
| `app/Repositories/Order/OrderRepository.php` | `findLastOrderByOrderDate` (`LIKE` on JSON), `findLastOrderByOrderNumber`, `orderAcceptedCount`, `orderDeliveries` sub-selects | query-level business logic |
| `app/Repositories/Order/UserDeliveriesRepository.php` | sub-select aggregates | delivery stats logic |
| `app/Managers/OrderCompositeManager.php` | `DB::transaction`, `activity()->disableLogging()`, `updateOrderStatus()` promotion to `production_completed` | transaction + audit + status promotion |
| `app/Processor/Order/OrderCreatorProcessor.php` | `:48 $requst`, `ProcessorExtension` static `$attributes` | bug D1 + shared-state pattern |
| `app/Processor/Order/OrderDraftCreatorProcessor.php` | `:37 $requst` | bug D1 |
| `app/Services/Order/OrderFindWithCommentService.php` | `:44-46 $requestData` | bug D2 |
| `app/Http/Controllers/Api/Delivery/DeliveriesByUserController.php` | `:31,:36` | bug D3 |
| `app/Http/Controllers/Api/Import/Dooglys/ImportDooglysMenuController.php` | commented dispatch + `DooglysSalePointImportJob` | bug D4 |
| `app/Services/VK/VkService.php` vs `app/Services/VK/VkApiClient.php` | `:22,:28` vs `:33,:46` | bug D5 (interface/client mismatch) |
| `app/Integration/Dooglys/DooglysApiClient.php` | `:60 VERIFY=false`, `:74 getResponse()` | security + bug D6 |
| `app/Http/Middleware/UserActivity.php` | `:37 Cache::put(key, value)` no TTL | cache design D7 |
| `app/Http/Responses/ApiResponse.php` | `:18 int $total = null`, `:35 string $message = null`; `ApiResponseInterface.php:22` | PHP 8.4 implicit-nullable deprecations (D18/M20) |
| `app/Console/Commands/Notification/ClearIrrelevantReadNotificationsCommand.php` | reads `NOTIFICATION__UNREAD_TIMEOUT`, compares `NOTIFICATION__READ_TIMEOUT` | bug D9 |
| `app/Services/Order/NumberGenerator/OrderNumberGeneratorService.php` | count + month | rule R4 / risk D10 |
| `app/Listeners/OrderEntityEventListener.php`, `app/Listeners/UserRevokeAllTokensDueToPermissionsUpdate.php` | all-users loops | fan-out D12 |
| `app/Jobs/OrderOverdueNotificationJob.php` | `queue = overdue_notifications`, `tries = 1`, `release()` | recurring-notification pattern (§8.4) |
| `app/Services/Order/Export/{OrderReportExporterService,AcceptedOrdersReportService}.php` | `limit 100000`, `toArray()` | memory risk D14 |
| `app/Http/Controllers/Api/Order/Report/OrderReportController.php` | try/catch → `responseError()` with 200 | contract risk D15 |
| `database/seeders/PermissionsTableSeeder.php` | 37 permissions incl. `*.delete`, `files.*`, `notifications.view`, `users_report.view` | permission drift D16 |
| `config/queue.php` | `:19 default sync`, `:57 dooglys_import`, `:65 redis` | queue topology |
| `config/database.php` | `:120-136 redis (phpredis)`, mysql `strict => true` | Redis client assumption |
| `config/broadcasting.php`, `config/websockets.php`, `config/horizon.php` | pusher + websockets + horizon config | real-time config; **horizon package absent** |
| `config/dooglys.php` | `DOOGLYS_API_URL` / `DOOGLYS_API_TOKEN` | env-name drift vs `.env.example` (`DOOGLYS_BASE_URI`) |
| `config/filesystems.php` | `local` has no `url`, `public` has `url` | `File::url()` disk assumption risk |
| `config/sanctum.php` | single `web` guard, expiration 0 | token lifecycle D17 |
| `docker-compose.yml` | `:69 mysql:5`, `:78 mysql.cnf` (missing file), `:29-43` redis block for websockets container | infra gaps B8/B10 |
| `docker/php/Dockerfile` | `:1 php:8.2-fpm-alpine`, `:19-29` extension list | runtime + missing ext-redis/amqp (B2/B11) |
| `docker/php/php.ini` | `date.timezone = UTC` | timezone drift (§5.5) |
| `docker/php/supervisor/{laravel-worker,laravel-websockets,nginx,cron}.conf` | worker + websockets + cron | process topology |
| `phpunit.xml` | suites → `tests/Feature`, `tests/Unit`; coverage white-list | suites point at absent dirs |
| `git ls-files tests/` | empty | **no tests** (B7) |
| `tests/App/Http/Controllers/Api/Order/*.php` (untracked) | request payload fixtures only | not runnable tests |
| `.env.example` | `CACHE_DRIVER=file`, `QUEUE_CONNECTION=sync`, `BROADCAST_DRIVER=log`, `CACHE_PREFIX=crm_back`, `DOOGLYS_BASE_URI`, `DOOGLYS_QUEUE_CONNECTION` | checked-in environment defaults |
| `storage/framework/{cache,testing,logs}`, `storage/fonts` | file cache + DomPDF fonts | runtime artifacts |

**External verification sources (`[External]`):**
- Packagist metadata (2026-08-14): `laravel/framework` latest 13.42.0 (`php ^8.3`, phpunit ^12.0, guzzle ^7.8.2|^8, symfony 7.4/8.0, carbon ^3.6, `serializable_classes` default false); `beyondcode/laravel-websockets` `abandoned: true` with replacement declared, dev-master targets `illuminate ^9`; `phpunit` latest 13.4.6 (PHP ≥ 8.4.1), 12.4.9 (PHP ≥ 8.2); `maatwebsite/excel` latest 4.0.6 (`php ^8.3`, `illuminate ^12|^13`), 3.1.70 supports `illuminate ^10|^11|^12|^13`; `spatie/laravel-activitylog` 5.9.0 (`php ^8.4`, `illuminate ^12|^13`); `pusher/pusher-php-server` 7.3.0.
- Official upgrade guides: Laravel 11.x (Sanctum ^4 + published migrations, DBAL removable, `->change()` modifier loss, floating-point types, application structure, Carbon 2/3 both supported, password rehashing, sync-after-commit), 12.x (Carbon 3 mandatory, phpunit ^11), 13.x (`serializable_classes`, cache prefix, collection serialization restoring eager loads, polymorphic pivot naming, MySQL DELETE JOIN, `Container::call` nullable defaults, `QueueBusy`/`BatchBusy`/`JobAttempted` payload renames, pagination view names).
- PHP release data: PHP 8.5 series exists with 8.5.11 as the newest listed entry; PHP supported-version list shows active series 8.3 / 8.4 / 8.5 (8.6 already released as current).

---

## 13. Confirmed facts vs hypotheses (quick index)

**Confirmed (`[Fact]`):** Laravel 10.50.2 locked; host PHP 8.4.22 vs container PHP 8.2.22; Sanctum 3.3.3; Excel pinned 3.1.67; DBAL 3.10.5; PHPUnit 9.6.34 with no tests in the repo; Redis/Horizon/RabbitMQ packages absent while Redis + Horizon configs exist; queue default `sync` with `dooglys_import` connection; cache driver `file` in checked-in env; 106 active endpoints + commented VK/task-board groups; custom middleware stack with priority override; token-ability RBAC with attribute middleware; event fan-out over all users; events dispatched inside transactions; 5 scheduled commands (1 commented); 6 console commands; 44 migrations / 15 seeders; latent defects D1-D22; infra mismatches B8-B11.

**Hypotheses needing confirmation (no runtime evidence available):** production values of `CACHE_DRIVER`/`QUEUE_CONNECTION`/`BROADCAST_DRIVER`; Dooglys endpoint payloads and TLS intent; whether the 9 order statuses form a state machine; whether global notification fan-out is intended; inspector assignment intent; date-limit `full`/`partial` semantics; money unit and rounding; task-board scope; VK revival intent; MySQL production version and data-migration window; deployment shape and secret ownership; front-end contract expectations.

**Explicitly out of scope in this pass:** reading `docs/migration/*` (per instruction), installing dependencies, running `artisan`, executing tests, changing any file other than this report.

---

## 14. Suggested next actions (awaiting owner input)

1. Answer **Q1-Q8** (blocking) — target PHP/Laravel, MySQL version, RabbitMQ driver choice, authorization parity, token lifecycle, notification audience, real-time replacement.
2. Approve Stage 0 (baseline tests + CI + snapshots) — it is the only way to make the upgrade verifiable, since the repo currently has zero tests.
3. Decide whether latent defects (D1-D9) are fixed **before** the upgrade (recommended: fix with tests pinning intended behaviour) or preserved as-is for parity.
4. Confirm dead-code deletion list (D20) and legacy V1 controller removal.
5. Confirm module boundaries in §10 before any refactor commit.
