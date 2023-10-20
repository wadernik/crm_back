<?php

use App\Http\Controllers\Api\Activity\ActivityController;
use App\Http\Controllers\Api\Activity\ActivityDictionaryController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Board\Board\BoardController;
use App\Http\Controllers\Api\Board\Group\GroupController;
use App\Http\Controllers\Api\Import\MenuImportController;
use App\Http\Controllers\Api\Import\SellerImportController;
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
use App\Http\Controllers\Api\Unit\UnitDictionaryController;
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
Route::prefix('vk')->group(static function () {
    Route::get('authorize', [AuthVkAppController::class, 'auth']);
    Route::get('redirect', [CreateOrUpdateVkTokenController::class, 'createOrUpdate']);
    Route::delete('logout', [RemoveVkTokenController::class, 'destroy']);
});

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
        Route::get('', [NotificationController::class, 'list']);
        Route::get('unread', [NotificationController::class, 'listUnread']);
        Route::post('read/{id}', NotificationMarkAsReadController::class)->whereNumber('id');
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
});

/**
 * Dictionaries
 */
Route::prefix('dictionary')->group(static function () {
    /** Auth is required */
    Route::middleware(['auth:sanctum'])->group(static function () {
        Route::get('roles', RoleDictionaryController::class);
        Route::get('permissions', PermissionDictionaryController::class);
        Route::get('orders/status', [OrderDictionaryController::class, 'statuses']);
        Route::get('orders/titles', [OrderDictionaryController::class, 'titles']);
        Route::get('manufacturers', ManufacturerDictionaryController::class);
        Route::get('manufacturers/limits', DateLimitDictionaryController::class);
        Route::get('sellers', SellerDictionaryController::class);
        Route::get('activities', ActivityDictionaryController::class);
        Route::get('units', UnitDictionaryController::class);
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

    Route::prefix('orders/drafts')->controller(OrderDraftController::class)->group(static function () {
        Route::get('', 'index');
        Route::get('{id}', 'show');
        Route::post('', 'store');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('orders')->group(static function () {
        Route::controller(OrderController::class)->group(static function () {
            Route::get('', 'index');
            Route::get('{id}', 'show');
            Route::post('', 'store');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
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