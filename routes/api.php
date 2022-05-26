<?php

use App\Http\Controllers\Api\FilesController;
use App\Http\Controllers\Api\ManufacturersController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\OrdersDraftsController;
use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\Api\ProfilesController;
use App\Http\Controllers\Api\Reports\UsersReportController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\SellersController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Авторизация
Route::group(
    [
        'prefix' => 'auth',
    ],
    static function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
        Route::post('/refresh', [AuthController::class, 'refresh'])->middleware(['auth:sanctum']);
    }
);

// Профиль пользователя
Route::group(
    [
        'prefix' => 'profile',
        'middleware' => ['auth:sanctum'],
    ],
    static function() {
        Route::get('/', [ProfilesController::class, 'show']);
        Route::put('/{id}', [ProfilesController::class, 'update']);
    }
);

// Общие методы
Route::group(
    [
        'middleware' => ['auth:sanctum'],
    ],
    static function () {
        Route::apiResource('users', UsersController::class);
        Route::apiResource('roles', RolesController::class);
        Route::apiResource('sellers', SellersController::class);
        Route::apiResource('manufacturers', ManufacturersController::class);

        // Заказы
        Route::apiResource('orders', OrdersController::class);
        Route::apiResource('orders_drafts', OrdersDraftsController::class);
        Route::post('orders/export', [OrdersController::class, 'export']);

        // Загрузка фотографий
        Route::post('/upload', [FilesController::class, 'upload']);
    }
);

// Отчеты
Route::group(
    [
        'prefix' => 'reports',
        'middleware' => ['auth:sanctum'],
    ],
    static function () {
        Route::get('/users', [UsersReportController::class, 'index']);
    }
);

// Справочники с авторизацией
Route::group(
    [
        'prefix' => 'dictionary',
        'middleware' => ['auth:sanctum'],
    ],
    static function () {
        Route::get('/roles', [RolesController::class, 'all']);
        Route::get('/permissions', [PermissionsController::class, 'all']);
        Route::get('/order_statuses', [OrdersController::class, 'statuses']);
        Route::get('/manufacturers', [ManufacturersController::class, 'all']);
        Route::get('/sellers', [SellersController::class, 'all']);
    }
);

// Справочники без авторизации
Route::group(
    [
        'prefix' => 'dictionary',
    ],
    static function () {
        Route::get('/users', [UsersController::class, 'all']);
    }
);
