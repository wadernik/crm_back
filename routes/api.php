<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Manufacturer\ManufacturerController;
use App\Http\Controllers\Api\Manufacturer\ManufacturerDictionaryController;
use App\Http\Controllers\Api\ManufacturerDateLimit\DateLimitController;
use App\Http\Controllers\Api\ManufacturerDateLimit\DateLimitDictionaryController;
use App\Http\Controllers\Api\Order\ExportOrderController;
use App\Http\Controllers\Api\Order\OrderController;
use App\Http\Controllers\Api\Order\UpdateOrderStatusController;
use App\Http\Controllers\Api\OrderDraft\OrderDraftController;
use App\Http\Controllers\Api\Permission\PermissionDictionaryController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Role\RoleController;
use App\Http\Controllers\Api\Role\RoleDictionaryController;
use App\Http\Controllers\Api\Seller\SellerController;
use App\Http\Controllers\Api\Seller\SellerDictionaryController;
use App\Http\Controllers\Api\Upload\UploadController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\User\UserDictionaryController;
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

/**
 * Авторизация VK
 */
// Route::group(
//     [
//         'prefix' => 'vk',
//     ],
//     static function() {
//         Route::get('/authorize', [VkController::class, 'authorizeAppLink']);
//         Route::get('/redirect', [VkController::class, 'catchRedirect']);
//         Route::delete('/logout', [VkController::class, 'removeToken']);
//     }
// );

/**
 * Профиль пользователя
 */
Route::group(
    [
        'prefix' => 'profile',
        'middleware' => ['auth:sanctum'],
    ],
    static function() {
        Route::get('/', [ProfileController::class, 'profile']);
        Route::put('/{id}', [ProfileController::class, 'update']);
        Route::delete('/logout/{id}', [ProfileController::class, 'revokeDevice']);
    }
);

/**
 * Общие методы
 */
Route::group(
    [
        'middleware' => ['auth:sanctum'],
    ],
    static function () {
        Route::resources([
            'users' => UserController::class,
            'manufacturers' => ManufacturerController::class,
            'roles' => RoleController::class,
            'sellers' => SellerController::class,
            'manufacturers_limits' => DateLimitController::class,
            'orders' => OrderController::class,
            'orders_drafts' => OrderDraftController::class,
        ]);

        // Загрузка фотографий
        Route::post('/upload', [UploadController::class, 'upload']);

        // Заказы
        Route::post('orders/export', [ExportOrderController::class, 'export']);
        Route::post('orders/status', [UpdateOrderStatusController::class, 'updateStatus']);
        // Route::get('orders/{id}/logs', [OrdersController::class, 'activities']);
        // Route::get('orders/{id}/comments', [OrderCommentsController::class, 'getComments']);
        // Route::post('orders/{id}/comments', [OrderCommentsController::class, 'postComment']);
        // Route::put('orders/{orderId}/comments/{commentId}', [OrderCommentsController::class, 'editComment']);
        // Route::delete('orders/{orderId}/comments/{commentId}', [OrderCommentsController::class, 'deleteComment']);

        // Логи
        // Route::get('activities', [ActivitiesController::class, 'index']);
    }
);

// Отчеты
// Route::group(
//     [
//         'prefix' => 'reports',
//         'middleware' => ['auth:sanctum'],
//     ],
//     static function () {
//         Route::get('/users', [UsersReportController::class, 'index']);
//         Route::post('/users', [UsersReportController::class, 'export']);
//     }
// );

/**
 * Справочники с авторизацией
 */
Route::group(
    [
        'prefix' => 'dictionary',
        'middleware' => ['auth:sanctum'],
    ],
    static function () {
        Route::get('/roles', [RoleDictionaryController::class, 'all']);
        Route::get('/permissions', [PermissionDictionaryController::class, 'all']);
        // Route::get('/order_statuses', [OrdersController::class, 'statuses']);
        Route::get('/manufacturers', [ManufacturerDictionaryController::class, 'all']);
        Route::get('/sellers', [SellerDictionaryController::class, 'all']);
        Route::get('/manufacturers_limit_types', [DateLimitDictionaryController::class, 'limitTypes']);
        // Route::get('/activities', [ActivitiesController::class, 'listSubjects']);
    }
);

/**
 * Справочники без авторизации
 */
Route::group(
    [
        'prefix' => 'dictionary',
    ],
    static function () {
        Route::get('/users', [UserDictionaryController::class, 'all']);
    }
);