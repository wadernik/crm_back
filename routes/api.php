<?php

use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

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
    }
);

// Справочники
Route::group(
    [
        'prefix' => 'dictionary',
    ],
    static function () {
        Route::get('/users', [UsersController::class, 'all']);
        Route::get('/roles', [RolesController::class, 'all']);
    }
);
