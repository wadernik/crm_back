<?php

use App\Http\Controllers\Api\Activity\ActivityController;
use App\Http\Controllers\Api\Activity\ActivityDictionaryController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Board\Board\BoardController;
use App\Http\Controllers\Api\Board\Group\GroupController;
use App\Http\Controllers\Api\Delivery\DeliveriesByUserController;
use App\Http\Controllers\Api\Delivery\DeliveriesByUsersController;
use App\Http\Controllers\Api\Import\MenuImportController;
use App\Http\Controllers\Api\Import\SellerImportController;
use App\Http\Controllers\Api\Manufacturer\ManufacturerController;
use App\Http\Controllers\Api\Manufacturer\ManufacturerDictionaryController;
use App\Http\Controllers\Api\ManufacturerDateLimit\DateLimitController;
use App\Http\Controllers\Api\ManufacturerDateLimit\DateLimitDictionaryController;
use App\Http\Controllers\Api\Notification\NotificationMarkAllAsReadController;
use App\Http\Controllers\Api\Notification\NotificationMarkAsReadController;
use App\Http\Controllers\Api\Notification\V2\CountUnreadNotificationController;
use App\Http\Controllers\Api\Notification\V2\ListNotificationController;
use App\Http\Controllers\Api\Order\Comment\DeleteOrderCommentController;
use App\Http\Controllers\Api\Order\Comment\EditOrderCommentController;
use App\Http\Controllers\Api\Order\Comment\ListOrderCommentController;
use App\Http\Controllers\Api\Order\Comment\PostOrderCommentController;
use App\Http\Controllers\Api\Order\ExportOrderController;
use App\Http\Controllers\Api\Order\OrderActivityController;
use App\Http\Controllers\Api\Order\OrderCounterController;
use App\Http\Controllers\Api\Order\OrderDeliverySetCourierController;
use App\Http\Controllers\Api\Order\OrderDictionaryController;
use App\Http\Controllers\Api\Order\Product\DeleteOrderProductController;
use App\Http\Controllers\Api\Order\Product\ListOrderProductController;
use App\Http\Controllers\Api\Order\Product\ListPendingOrderProductController;
use App\Http\Controllers\Api\Order\Product\RestoreOrderProductController;
use App\Http\Controllers\Api\Order\UpdateOrderStatusController;
use App\Http\Controllers\Api\Order\V2\CreateOrderController;
use App\Http\Controllers\Api\Order\V2\DeleteOrderController;
use App\Http\Controllers\Api\Order\V2\GetOrderController;
use App\Http\Controllers\Api\Order\V2\ListOrderController;
use App\Http\Controllers\Api\Order\V2\UpdateOrderController;
use App\Http\Controllers\Api\OrderDraft\V2\CreateOrderDraftController;
use App\Http\Controllers\Api\OrderDraft\V2\DeleteOrderDraftController;
use App\Http\Controllers\Api\OrderDraft\V2\GetOrderDraftController;
use App\Http\Controllers\Api\OrderDraft\V2\ListOrderDraftController;
use App\Http\Controllers\Api\OrderDraft\V2\UpdateOrderDraftController;
use App\Http\Controllers\Api\OrderSetting\OrderSettingController;
use App\Http\Controllers\Api\OrderSetting\SettingDictionaryController;
use App\Http\Controllers\Api\Permission\PermissionDictionaryController;
use App\Http\Controllers\Api\Permission\Section\PermissionSectionDictionaryController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Role\RoleController;
use App\Http\Controllers\Api\Role\RoleDictionaryController;
use App\Http\Controllers\Api\Seller\SellerController;
use App\Http\Controllers\Api\Seller\SellerDictionaryController;
use App\Http\Controllers\Api\Setting\CreateSettingController;
use App\Http\Controllers\Api\Setting\DeleteSettingController;
use App\Http\Controllers\Api\Setting\GetSettingController;
use App\Http\Controllers\Api\Setting\ListSettingController;
use App\Http\Controllers\Api\Setting\ListSettingDictionaryController;
use App\Http\Controllers\Api\Setting\UpdateSettingController;
use App\Http\Controllers\Api\Unit\UnitDictionaryController;
use App\Http\Controllers\Api\Upload\UploadController;
use App\Http\Controllers\Api\User\ExportUserReportController;
use App\Http\Controllers\Api\User\ListUserReportController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\User\UserDictionaryController;
use App\Http\Controllers\Api\User\UserStatusDictionaryController;
use Illuminate\Support\Facades\Route;

/**
 * Auth
 */
Route::prefix('auth')->group(static function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
});

/**
 * Auth VK
 */
// Route::prefix('vk')->group(static function () {
    // Route::get('authorize', [AuthVkAppController::class, 'auth']);
    // Route::get('redirect', [CreateOrUpdateVkTokenController::class, 'createOrUpdate']);
    // Route::delete('logout', [RemoveVkTokenController::class, 'destroy']);
// });

/**
 * Endpoints with required authorization
 */
Route::middleware(['auth:sanctum'])->group(static function () {
    /**
     * Profile
     */
    Route::prefix('profile')->controller(ProfileController::class)->group(static function () {
        Route::get('', 'profile');
        Route::put('{id}', 'update');
        Route::delete('logout/{id}', 'revokeDevice');
    });

    /**
     * Notifications
     */
    Route::prefix('notifications')->group(static function () {
        Route::get('', ListNotificationController::class);
        Route::get('unread', CountUnreadNotificationController::class);
        Route::post('read/{id}', NotificationMarkAsReadController::class)->whereUuid('id');
        Route::post('read', NotificationMarkAllAsReadController::class);
    });

    /**
     * Import
     */
    Route::prefix('import')->group(static function () {
        Route::get('sellers', SellerImportController::class);
        Route::get('menu', MenuImportController::class);
    });

    /**
     * Reports
     */
    Route::prefix('reports')->group(static function () {
        Route::get('users', ListUserReportController::class);
        Route::post('users', ExportUserReportController::class);
    });

    /** File uploads */
    Route::post('upload', UploadController::class);

    /** Logs */
    Route::get('activities', ActivityController::class);

    /** Delivery statistics */
    Route::prefix('deliveries')->middleware(['sanctum.permissions'])->group(static function () {
        Route::get('{user_id}', DeliveriesByUserController::class);
        Route::get('', DeliveriesByUsersController::class);
    });
});

/**
 * Dictionaries
 */
Route::prefix('dictionary')->group(static function () {
    /** Auth is required */
    Route::middleware(['auth:sanctum'])->group(static function () {
        Route::get('roles', RoleDictionaryController::class);
        Route::get('permissions', PermissionDictionaryController::class);
        Route::get('permissions/sections', PermissionSectionDictionaryController::class);
        Route::get('orders/status', [OrderDictionaryController::class, 'statuses']);
        Route::get('orders/titles', ListOrderProductController::class);
        Route::get('orders/settings', SettingDictionaryController::class);
        Route::get('orders/contacts', [OrderDictionaryController::class, 'contactTypes']);
        Route::get('orders/decorations', [OrderDictionaryController::class, 'decorationTypes']);
        Route::get('manufacturers', ManufacturerDictionaryController::class);
        Route::get('manufacturers/limits', DateLimitDictionaryController::class);
        Route::get('sellers', SellerDictionaryController::class);
        Route::get('activities', ActivityDictionaryController::class);
        Route::get('units', UnitDictionaryController::class);
        Route::get('settings', ListSettingDictionaryController::class);

        Route::get('orders/titles/pending', ListPendingOrderProductController::class);
        Route::post('orders/titles/restore', RestoreOrderProductController::class);
        Route::post('orders/titles/delete', DeleteOrderProductController::class);
    });

    /** Without auth */
    Route::get('users', UserDictionaryController::class);
    Route::get('users/status', UserStatusDictionaryController::class);
});

/**
 * Resources with required authorization
 */
Route::middleware(['auth:sanctum'])->group(static function () {
    Route::prefix('users')->controller(UserController::class)->group(static function () {
        Route::get('', 'index');
        Route::get('{id}', 'show');
        Route::post('', 'store');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('manufacturers')->controller(ManufacturerController::class)->group(static function () {
        Route::get('', 'index');
        Route::get('{id}', 'show');
        Route::post('', 'store');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('manufacturers/limits')->controller(DateLimitController::class)->group(static function () {
        Route::get('', 'index');
        Route::get('{id}', 'show');
        Route::post('', 'store');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('roles')->controller(RoleController::class)->group(static function () {
        Route::get('', 'index');
        Route::get('{id}', 'show');
        Route::post('', 'store');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('sellers')->controller(SellerController::class)->group(static function () {
        Route::get('', 'index');
        Route::get('{id}', 'show');
        Route::post('', 'store');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('orders/settings')->controller(OrderSettingController::class)->group(static function () {
        Route::get('', 'index');
        Route::get('{id}', 'show');
        Route::post('', 'store');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('settings')->middleware(['sanctum.permissions'])->group(static function () {
        Route::get('{id}', GetSettingController::class);
        Route::get('', ListSettingController::class);
        Route::post('', CreateSettingController::class);
        Route::put('{id}', UpdateSettingController::class);
        Route::delete('{id}', DeleteSettingController::class);
    });

    Route::prefix('orders/drafts')->middleware(['sanctum.permissions'])->group(static function () {
        Route::post('', CreateOrderDraftController::class);
        Route::put('{id}', UpdateOrderDraftController::class);
        Route::delete('{id}', DeleteOrderDraftController::class);
        Route::get('{id}', GetOrderDraftController::class);
        Route::get('', ListOrderDraftController::class);
    });

    Route::prefix('orders')->middleware(['sanctum.permissions'])->group(static function () {
        Route::middleware(['sanctum.permissions'])->group(static function () {
            Route::post('', CreateOrderController::class);
            Route::put('{id}', UpdateOrderController::class);
            Route::delete('{id}', DeleteOrderController::class);
            Route::get('{id}', GetOrderController::class);
            Route::get('', ListOrderController::class);
            Route::post('{id}/deliver', OrderDeliverySetCourierController::class);
            Route::get('counter', OrderCounterController::class);
        });

        Route::post('export', ExportOrderController::class);
        Route::post('status', UpdateOrderStatusController::class);
        Route::get('{id}/logs', OrderActivityController::class);
        Route::get('{id}/comments', ListOrderCommentController::class);
        Route::post('{id}/comments', PostOrderCommentController::class);
        Route::put('{orderId}/comments/{commentId}', EditOrderCommentController::class)
            ->whereNumber(['orderId', 'commentId']);
        Route::delete('{orderId}/comments/{commentId}', DeleteOrderCommentController::class)
            ->whereNumber(['orderId', 'commentId']);
    });
});

/**
 * Task board endpoints
 */
Route::middleware(['auth:sanctum'])->prefix('task-boards')->group(static function () {
    Route::prefix('boards')->controller(BoardController::class)->group(static function () {
        Route::get('', 'index');
        Route::get('{id}', 'show');
        Route::post('', 'store');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('boards/{boardId}/groups')
        ->whereNumber('boardId')
        ->controller(GroupController::class)->group(static function () {
            Route::get('', 'index');
            Route::get('{id}', 'show');
            Route::post('', 'store');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
        });
});