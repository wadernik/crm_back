<?php

use App\Http\Controllers\Api\FilesController;
use App\Http\Controllers\Api\ManufacturersController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\OrdersController2;
use App\Http\Controllers\Api\PermissionsController;
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
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh'])->middleware(['auth:sanctum']);
    }
);

// Админ методы
Route::group(
    [
        'middleware' => ['auth:sanctum'],
    ],
    static function () {
        Route::apiResource('users', UsersController::class);
        Route::apiResource('roles', RolesController::class);
        Route::apiResource('sellers', SellersController::class);
        Route::apiResource('manufacturers', ManufacturersController::class);
        Route::apiResource('orders_new', OrdersController2::class);
    }
);

// Общие методы
Route::group(
    [
        'middleware' => ['auth:sanctum'],
    ],
    static function () {
        Route::post('/upload', [FilesController::class, 'uploadFile']);
        Route::post('/upload_several', [FilesController::class, 'uploadFiles']);
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
        Route::get('/order_statuses_new', [OrdersController2::class, 'statuses']);
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
