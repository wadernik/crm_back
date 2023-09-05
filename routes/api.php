<?php

use App\Http\Controllers\Api\Activity\ActivityController;
use App\Http\Controllers\Api\Activity\ActivityDictionaryController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Manufacturer\ManufacturerController;
use App\Http\Controllers\Api\Manufacturer\ManufacturerDictionaryController;
use App\Http\Controllers\Api\ManufacturerDateLimit\DateLimitController;
use App\Http\Controllers\Api\ManufacturerDateLimit\DateLimitDictionaryController;
use App\Http\Controllers\Api\Notification\NotificationController;
use App\Http\Controllers\Api\Notification\NotificationMarkAllAsReadController;
use App\Http\Controllers\Api\Notification\NotificationMarkAsReadController;
use App\Http\Controllers\Api\Order\Comment\DeleteOrderCommentController;
use App\Http\Controllers\Api\Order\Comment\EditOrderCommentController;
use App\Http\Controllers\Api\Order\Comment\ListOrderCommentController;
use App\Http\Controllers\Api\Order\Comment\PostOrderCommentController;
use App\Http\Controllers\Api\Order\ExportOrderController;
use App\Http\Controllers\Api\Order\OrderActivityController;
use App\Http\Controllers\Api\Order\OrderController;
use App\Http\Controllers\Api\Order\OrderDictionaryController;
use App\Http\Controllers\Api\Order\UpdateOrderStatusController;
use App\Http\Controllers\Api\OrderDraft\OrderDraftController;
use App\Http\Controllers\Api\Permission\PermissionDictionaryController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Role\RoleController;
use App\Http\Controllers\Api\Role\RoleDictionaryController;
use App\Http\Controllers\Api\Seller\SellerController;
use App\Http\Controllers\Api\Seller\SellerDictionaryController;
use App\Http\Controllers\Api\Upload\UploadController;
use App\Http\Controllers\Api\User\ExportUserReportController;
use App\Http\Controllers\Api\User\ListUserReportController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\User\UserDictionaryController;
use App\Http\Controllers\Api\User\UserStatusDictionaryController;
use App\Http\Controllers\Api\VK\AuthVkAppController;
use App\Http\Controllers\Api\VK\CreateOrUpdateVkTokenController;
use App\Http\Controllers\Api\VK\RemoveVkTokenController;
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
Route::group(
    [
        'prefix' => 'vk',
    ],
    static function() {
        Route::get('/authorize', [AuthVkAppController::class, 'auth']);
        Route::get('/redirect', [CreateOrUpdateVkTokenController::class, 'createOrUpdate']);
        Route::delete('/logout', [RemoveVkTokenController::class, 'destroy']);
    }
);

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
 * Уведомления
 */
Route::group(
    [
        'prefix' => 'notifications',
        'middleware' => ['auth:sanctum'],
    ],
    static function() {
        Route::get('/', [NotificationController::class, 'list']);
        Route::get('/unread', [NotificationController::class, 'listUnread']);
        Route::post('/read/{id}', NotificationMarkAsReadController::class);
        Route::post('/read', NotificationMarkAllAsReadController::class);
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
        Route::get('orders/{id}/logs', [OrderActivityController::class, 'activities']);
        Route::get('orders/{id}/comments', [ListOrderCommentController::class, 'comments']);
        Route::post('orders/{id}/comments', [PostOrderCommentController::class, 'comment']);
        Route::put('orders/{orderId}/comments/{commentId}', [EditOrderCommentController::class, 'edit']);
        Route::delete('orders/{orderId}/comments/{commentId}', [DeleteOrderCommentController::class, 'destroy']);

        // Логи
        Route::get('activities', [ActivityController::class, 'index']);
    }
);

// Отчеты
Route::group(
    [
        'prefix' => 'reports',
        'middleware' => ['auth:sanctum'],
    ],
    static function () {
        Route::get('/users', [ListUserReportController::class, 'list']);
        Route::post('/users', [ExportUserReportController::class, 'export']);
    }
);

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
        Route::get('/order_statuses', [OrderDictionaryController::class, 'statuses']);
        Route::get('/manufacturers', [ManufacturerDictionaryController::class, 'all']);
        Route::get('/sellers', [SellerDictionaryController::class, 'all']);
        Route::get('/manufacturers_limit_types', [DateLimitDictionaryController::class, 'limitTypes']);
        Route::get('/activities', [ActivityDictionaryController::class, 'subjects']);
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
        Route::get('/users/status', [UserStatusDictionaryController::class, 'statuses']);
    }
);