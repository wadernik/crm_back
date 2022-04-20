## Simple backend based on Laravel for CRM systems

This project is a simple REST API backend based on Laravel which features tools
for managing internal business processes.

## Implemented:
- Token based auth
- User's control methods with roles and permissions (for staff)
- Manufacturer's control methods (one who fulfills placed orders)
- Seller's control methods
- Order's processing methods (partially; there are drafted and completed orders)
- File uploading methods

## Planned:
- Calendar (a flexible way to show summary info about orders grouped by date)
- Notifications
- Reports
- Chat (?)
- Catalogue (future iterations)

## Installation:

Install composer dependencies
```sh
composer install
```

Create and configure the `.env` file based on `.env.example`

Run migrations: `:fresh` is optional
```sh
php artisan migrate:fresh
```

Seed the DB with temporarily dev data:
```sh
php artisan db:seed
```

Run the built-in server or configure external web server
```sh
php artisan serve
```

## Additionals:
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Parental (STI implementation)](https://github.com/calebporzio/parental)
