<?php

namespace App\Providers;

use App\Factory\Profile\ProfileFactory;
use App\Factory\Profile\ProfileFactoryInterface;
use App\Integration\Dooglys\DooglysApiClient;
use App\Integration\Dooglys\DooglysApiClientInterface;
use App\Managers\Board\Board\BoardManager;
use App\Managers\Board\Board\BoardManagerInterface;
use App\Managers\Board\Group\GroupManager;
use App\Managers\Board\Group\GroupManagerInterface;
use App\Managers\Board\Task\TaskManager;
use App\Managers\Board\Task\TaskManagerInterface;
use App\Managers\Comment\CommentManager;
use App\Managers\Comment\CommentManagerInterface;
use App\Managers\Manufacturer\ManufacturerManager;
use App\Managers\Manufacturer\ManufacturerManagerInterface;
use App\Managers\ManufacturerDateLimit\DateLimitManager;
use App\Managers\ManufacturerDateLimit\DateLimitManagerInterface;
use App\Managers\Role\RoleManager;
use App\Managers\Role\RoleManagerInterface;
use App\Managers\Seller\SellerManager;
use App\Managers\Seller\SellerManagerInterface;
use App\Managers\Upload\UploadManager;
use App\Managers\Upload\UploadManagerInterface;
use App\Managers\User\UserManager;
use App\Managers\User\UserManagerInterface;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\Board\Board\BoardRepository;
use App\Repositories\Board\Board\BoardRepositoryInterface;
use App\Repositories\Board\Group\GroupRepository;
use App\Repositories\Board\Group\GroupRepositoryInterface;
use App\Repositories\Board\Task\TaskRepository;
use App\Repositories\Board\Task\TaskRepositoryInterface;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Dictionary\DictionaryRepository;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;
use App\Repositories\Manufacturer\ManufacturerRepository;
use App\Repositories\Manufacturer\ManufacturerRepositoryInterface;
use App\Repositories\ManufacturerDateLimit\DateLimitRepository;
use App\Repositories\ManufacturerDateLimit\DateLimitRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Permission\Section\PermissionSectionRepository;
use App\Repositories\Permission\Section\PermissionSectionRepositoryInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Seller\SellerRepository;
use App\Repositories\Seller\SellerRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Activity\ActivityService;
use App\Services\Activity\ActivityServiceInterface;
use App\Services\Auth\AuthUserService;
use App\Services\Auth\AuthUserServiceInterface;
use App\Services\Dooglys\Menu\DooglysMenuImportService;
use App\Services\Dooglys\Menu\DooglysMenuImportServiceInterface;
use App\Services\Dooglys\Order\DooglysOrderSyncService;
use App\Services\Dooglys\Order\DooglysOrderSyncServiceInterface;
use App\Services\Dooglys\SalePoint\DooglysSalePointImportService;
use App\Services\Dooglys\SalePoint\DooglysSalePointImportServiceInterface;
use App\Services\Dooglys\Sub\DooglysMenuAction;
use App\Services\Dooglys\Sub\DooglysMenuActionInterface;
use App\Services\Dooglys\Sub\DooglysOrderActions;
use App\Services\Dooglys\Sub\DooglysOrderActionsInterface;
use App\Services\Dooglys\Sub\DooglysSalePointAction;
use App\Services\Dooglys\Sub\DooglysSalePointActionInterface;
use App\Services\Profile\ProfileService;
use App\Services\Profile\ProfileServiceInterface;
use App\Services\Profile\StyledUserAgentService;
use App\Services\Profile\StyledUserAgentServiceInterface;
use App\Services\User\ExportUserReportService;
use App\Services\User\ExportUserReportServiceInterface;
use App\Services\User\UserReportService;
use App\Services\User\UserReportServiceInterface;
use App\Services\VK\VkApiClient;
use App\Services\VK\VkApiClientInterface;
use App\Services\VK\VkService;
use App\Services\VK\VkServiceInterface;
use Illuminate\Support\ServiceProvider;
use function App\Helpers\Functions\load_service;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        /**
         * REPOSITORIES
         */
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(ManufacturerRepositoryInterface::class, ManufacturerRepository::class);
        $this->app->bind(SellerRepositoryInterface::class, SellerRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(DateLimitRepositoryInterface::class, DateLimitRepository::class);
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(DictionaryRepositoryInterface::class, DictionaryRepository::class);
        $this->app->bind(PermissionSectionRepositoryInterface::class, PermissionSectionRepository::class);

        // Task Boards
        $this->app->bind(BoardRepositoryInterface::class, BoardRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);

        /**
         * MANAGERS
         */
        $this->app->bind(UserManagerInterface::class, UserManager::class);
        $this->app->bind(RoleManagerInterface::class, RoleManager::class);
        $this->app->bind(UploadManagerInterface::class, UploadManager::class);
        $this->app->bind(ManufacturerManagerInterface::class, ManufacturerManager::class);
        $this->app->bind(SellerManagerInterface::class, SellerManager::class);
        $this->app->bind(DateLimitManagerInterface::class, DateLimitManager::class);
        $this->app->bind(CommentManagerInterface::class, CommentManager::class);

        // Task Board
        $this->app->bind(BoardManagerInterface::class, BoardManager::class);
        $this->app->bind(GroupManagerInterface::class, GroupManager::class);
        $this->app->bind(TaskManagerInterface::class, TaskManager::class);

        /**
         * SERVICES
         */
        $this->app->bind(AuthUserServiceInterface::class, AuthUserService::class);
        $this->app->bind(ActivityServiceInterface::class, ActivityService::class);
        $this->app->bind(StyledUserAgentServiceInterface::class, StyledUserAgentService::class);

        $this->app->bind(ProfileServiceInterface::class, function () {
            return new ProfileService(
                load_service(StyledUserAgentService::class),
                load_service(ProfileFactoryInterface::class)
            );
        });

        $this->app->bind(ProfileFactoryInterface::class, ProfileFactory::class);

        $this->app->bind(UserReportServiceInterface::class, function () {
            return new UserReportService(
                load_service(UserRepositoryInterface::class),
                load_service(OrderRepositoryInterface::class)
            );
        });

        $this->app->bind(ExportUserReportServiceInterface::class, ExportUserReportService::class);
        $this->app->bind(VkApiClientInterface::class, VkApiClient::class);
        $this->app->bind(VkServiceInterface::class, VkService::class);

        /** Dooglys */
        $this->app->bind(DooglysApiClientInterface::class, DooglysApiClient::class);

        $this->app->bind(DooglysOrderSyncServiceInterface::class, function () {
            return new DooglysOrderSyncService(load_service(DooglysOrderActionsInterface::class));
        });

        $this->app->bind(DooglysSalePointImportServiceInterface::class, function () {
            return new DooglysSalePointImportService(load_service(DooglysSalePointActionInterface::class));
        });

        $this->app->bind(DooglysMenuImportServiceInterface::class, function () {
            return new DooglysMenuImportService(
                load_service(DooglysMenuActionInterface::class),
                load_service(SellerRepositoryInterface::class)
            );
        });

        $this->app->bind(DooglysOrderActionsInterface::class, DooglysOrderActions::class);
        $this->app->bind(DooglysSalePointActionInterface::class, DooglysSalePointAction::class);
        $this->app->bind(DooglysMenuActionInterface::class, DooglysMenuAction::class);
    }
}