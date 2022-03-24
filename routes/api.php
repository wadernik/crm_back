<?php

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
    }
);

// Справочники
Route::group(
    [
        'prefix' => 'dictionary',
    ],
    static function () {
        Route::get('/users', function () {
            return \App\Models\User::query()
                ->get(['id', 'name', 'username'])
                ->toArray();
        });
    }
);

Route::group(
    [
        'middleware' => ['auth:sanctum', 'sanctum.permissions:users.view']
    ],
    static function () {
        Route::get('/users', function() {
            // @TODO: temporary stuff to remove later
            return \App\Models\User::query()
                // ->with('role')
                ->get(['id', 'name', 'phone', 'email', 'role_id'])
                // ->makeHidden(['role_id'])
                ->toArray();
        });
});
