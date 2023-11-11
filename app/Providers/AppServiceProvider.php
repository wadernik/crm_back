<?php

namespace App\Providers;

use App\Factory\Profile\ProfileFactory;
use App\Factory\Profile\ProfileFactoryInterface;
use App\Formatters\Notification\DatabaseNotificationFormatter;
use App\Formatters\Notification\DatabaseNotificationFormatterInterface;
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
use App\Managers\Order\BaseOrderManager;
use App\Managers\Order\BaseOrderManagerInterface;
use App\Managers\Order\Draft\OrderDraftManager;
use App\Managers\Order\Draft\OrderDraftManagerInterface;
use App\Managers\Order\Normal\OrderManager;
use App\Managers\Order\Normal\OrderManagerInterface;
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
use App\Repositories\Order\Filter\OrderFilter;
use App\Repositories\Order\Filter\OrderFilterInterface;
use App\Repositories\Order\Filter\OrderFilterProcessor;
use App\Repositories\Order\Filter\OrderFilterProcessorInterface;
use App\Repositories\Order\Item\OrderItemRepository;
use App\Repositories\Order\Item\OrderItemRepositoryInterface;
use App\Repositories\Order\OrderDraftRepository;
use App\Repositories\Order\OrderDraftRepositoryInterface;
use App\Repositories\Order\OrderRepository;
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
use App\Services\Order\Activity\OrderActivityService;
use App\Services\Order\Activity\OrderActivityServiceInterface;
use App\Services\Order\Checker\OrderCreationRestrictionChecker;
use App\Services\Order\Checker\OrderCreationRestrictionCheckerInterface;
use App\Services\Order\Checker\OrderFinalPriceChecker;
use App\Services\Order\Checker\OrderFinalPriceCheckerInterface;
use App\Services\Order\Checker\OrderStateChecker;
use App\Services\Order\Checker\OrderStateCheckerInterface;
use App\Services\Order\Export\OrderExportService;
use App\Services\Order\Export\OrderExportServiceInterface;
use App\Services\Order\ManagerExtension\BaseOrderCreatorService;
use App\Services\Order\ManagerExtension\BaseOrderCreatorServiceInterface;
use App\Services\Order\ManagerExtension\BaseOrderUpdaterService;
use App\Services\Order\ManagerExtension\BaseOrderUpdaterServiceInterface;
use App\Services\Order\ManagerExtension\Draft\OrderDraftCreatorService;
use App\Services\Order\ManagerExtension\Draft\OrderDraftCreatorServiceInterface;
use App\Services\Order\ManagerExtension\Draft\OrderDraftUpdaterService;
use App\Services\Order\ManagerExtension\Draft\OrderDraftUpdaterServiceInterface;
use App\Services\Order\ManagerExtension\Normal\OrderCreatorService;
use App\Services\Order\ManagerExtension\Normal\OrderCreatorServiceInterface;
use App\Services\Order\ManagerExtension\Normal\OrderUpdaterService;
use App\Services\Order\ManagerExtension\Normal\OrderUpdaterServiceInterface;
use App\Services\Order\OrderExtendAllWithTotalCommentsService;
use App\Services\Order\OrderExtendAllWithTotalCommentsServiceInterface;
use App\Services\Order\OrderFindWithCommentService;
use App\Services\Order\OrderFindWithCommentServiceInterface;
use App\Services\Order\OrderNumber\OrderNumberGeneratorService;
use App\Services\Order\OrderNumber\OrderNumberGeneratorServiceInterface;
use App\Services\Order\Processor\OrderFinalPriceProcessor;
use App\Services\Order\Processor\OrderFinalPriceProcessorInterface;
use App\Services\Order\Status\OrderStatusesRetriever;
use App\Services\Order\Status\OrderStatusesRetrieverInterface;
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

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
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
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderDraftRepositoryInterface::class, OrderDraftRepository::class);
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(OrderItemRepositoryInterface::class, OrderItemRepository::class);
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
        $this->app->bind(BaseOrderManagerInterface::class, BaseOrderManager::class);
        $this->app->bind(OrderManagerInterface::class, OrderManager::class);
        $this->app->bind(OrderDraftManagerInterface::class, OrderDraftManager::class);
        $this->app->bind(CommentManagerInterface::class, CommentManager::class);

        // Task Board
        $this->app->bind(BoardManagerInterface::class, BoardManager::class);
        $this->app->bind(GroupManagerInterface::class, GroupManager::class);
        $this->app->bind(TaskManagerInterface::class, TaskManager::class);

        /**
         * SERVICES
         */
        $this->app->bind(OrderFilterInterface::class, OrderFilter::class);
        $this->app->bind(OrderFilterProcessorInterface::class, OrderFilterProcessor::class);
        $this->app->bind(AuthUserServiceInterface::class, AuthUserService::class);
        $this->app->bind(ActivityServiceInterface::class, ActivityService::class);
        $this->app->bind(StyledUserAgentServiceInterface::class, StyledUserAgentService::class);
        $this->app->bind(ProfileServiceInterface::class, ProfileService::class);
        $this->app->bind(ProfileFactoryInterface::class, ProfileFactory::class);
        $this->app->bind(UserReportServiceInterface::class, UserReportService::class);
        $this->app->bind(ExportUserReportServiceInterface::class, ExportUserReportService::class);
        $this->app->bind(VkApiClientInterface::class, VkApiClient::class);
        $this->app->bind(VkServiceInterface::class, VkService::class);

        /** Order */
        $this->app->bind(BaseOrderCreatorServiceInterface::class, BaseOrderCreatorService::class);
        $this->app->bind(OrderDraftCreatorServiceInterface::class, OrderDraftCreatorService::class);
        $this->app->bind(OrderCreatorServiceInterface::class, OrderCreatorService::class);

        $this->app->bind(BaseOrderUpdaterServiceInterface::class, BaseOrderUpdaterService::class);
        $this->app->bind(OrderDraftUpdaterServiceInterface::class, OrderDraftUpdaterService::class);
        $this->app->bind(OrderUpdaterServiceInterface::class, OrderUpdaterService::class);

        $this->app->bind(OrderActivityServiceInterface::class, OrderActivityService::class);
        $this->app->bind(OrderCreationRestrictionCheckerInterface::class, OrderCreationRestrictionChecker::class);
        $this->app->bind(OrderStateCheckerInterface::class, OrderStateChecker::class);
        $this->app->bind(OrderExportServiceInterface::class, OrderExportService::class);

        $this->app->bind(OrderNumberGeneratorServiceInterface::class, OrderNumberGeneratorService::class);
        $this->app->bind(OrderFinalPriceProcessorInterface::class, OrderFinalPriceProcessor::class);
        $this->app->bind(OrderFindWithCommentServiceInterface::class, OrderFindWithCommentService::class);
        $this->app->bind(OrderStatusesRetrieverInterface::class, OrderStatusesRetriever::class);
        $this->app->bind(OrderFinalPriceCheckerInterface::class, OrderFinalPriceChecker::class);
        $this->app->bind(
            OrderExtendAllWithTotalCommentsServiceInterface::class,
            OrderExtendAllWithTotalCommentsService::class
        );
        $this->app->bind(DatabaseNotificationFormatterInterface::class, DatabaseNotificationFormatter::class);

        /** Dooglys */
        $this->app->bind(DooglysApiClientInterface::class, DooglysApiClient::class);
        $this->app->bind(DooglysOrderSyncServiceInterface::class, DooglysOrderSyncService::class);
        $this->app->bind(DooglysSalePointImportServiceInterface::class, DooglysSalePointImportService::class);
        $this->app->bind(DooglysMenuImportServiceInterface::class, DooglysMenuImportService::class);

        $this->app->bind(DooglysOrderActionsInterface::class, DooglysOrderActions::class);
        $this->app->bind(DooglysSalePointActionInterface::class, DooglysSalePointAction::class);
        $this->app->bind(DooglysMenuActionInterface::class, DooglysMenuAction::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}