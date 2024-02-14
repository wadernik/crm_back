<?php

namespace App\Providers\Order;

use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\OrderDraftDTO;
use App\DTOs\Order\UpdateOrderDTO;
use App\Formatters\Notification\DatabaseNotificationFormatter;
use App\Formatters\Notification\DatabaseNotificationFormatterInterface;
use App\Managers\Order\Draft\OrderDraftManagerInterface;
use App\Managers\Order\Normal\OrderManagerInterface;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Manufacturer\ManufacturerRepositoryInterface;
use App\Repositories\ManufacturerDateLimit\DateLimitRepositoryInterface;
use App\Repositories\Order\Filter\OrderFilter;
use App\Repositories\Order\Filter\OrderFilterInterface;
use App\Repositories\Order\Filter\OrderFilterProcessor;
use App\Repositories\Order\Filter\OrderFilterProcessorInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Seller\SellerRepositoryInterface;
use App\Services\Dooglys\Order\DooglysOrderSyncServiceInterface;
use App\Services\Order\Activity\OrderActivityService;
use App\Services\Order\Activity\OrderActivityServiceInterface;
use App\Services\Order\Checker\OrderCreationRestrictionByManufacturerChecker;
use App\Services\Order\Checker\OrderCreationRestrictionByManufacturerCheckerInterface;
use App\Services\Order\Checker\OrderFinalPriceChecker;
use App\Services\Order\Checker\OrderFinalPriceCheckerInterface;
use App\Services\Order\Checker\OrderSellerChecker;
use App\Services\Order\Checker\OrderSellerCheckerInterface;
use App\Services\Order\Checker\OrderStateChecker;
use App\Services\Order\Checker\OrderStateCheckerInterface;
use App\Services\Order\Contact\OrderContactTypeRetriever;
use App\Services\Order\Contact\OrderContactTypeRetrieverInterface;
use App\Services\Order\Export\OrderExportService;
use App\Services\Order\Export\OrderExportServiceInterface;
use App\Services\Order\ManagerExtension\BaseOrderCreatorService;
use App\Services\Order\ManagerExtension\BaseOrderUpdaterService;
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
use App\Services\OrderSetting\OrderSettingTypeRetriever;
use App\Services\OrderSetting\OrderSettingTypeRetrieverInterface;
use Illuminate\Support\ServiceProvider;
use function App\Helpers\Functions\load_service;

class OrderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderFilterInterface::class, OrderFilter::class);
        $this->app->bind(OrderFilterProcessorInterface::class, OrderFilterProcessor::class);

        $this->app->bind(OrderCreatorServiceInterface::class, function () {
            return new OrderCreatorService(
                new BaseOrderCreatorService(
                    load_service(OrderManagerInterface::class),
                    load_service(OrderCreationRestrictionByManufacturerCheckerInterface::class),
                    load_service(OrderSellerCheckerInterface::class),
                    load_service(OrderNumberGeneratorServiceInterface::class),
                    CreateOrderDTO::class
                )
            );
        });

        $this->app->bind(OrderDraftCreatorServiceInterface::class, function () {
            new OrderDraftCreatorService(
                new BaseOrderCreatorService(
                    load_service(OrderDraftManagerInterface::class),
                    load_service(OrderCreationRestrictionByManufacturerCheckerInterface::class),
                    load_service(OrderSellerCheckerInterface::class),
                    load_service(OrderNumberGeneratorServiceInterface::class),
                    OrderDraftDTO::class
                )
            );
        });

        $this->app->bind(OrderUpdaterServiceInterface::class, function () {
            return new OrderUpdaterService(
                new BaseOrderUpdaterService(
                    load_service(OrderManagerInterface::class),
                    load_service(OrderCreationRestrictionByManufacturerCheckerInterface::class),
                    load_service(OrderSellerCheckerInterface::class),
                    load_service(OrderFinalPriceCheckerInterface::class),
                    UpdateOrderDTO::class
                )
            );
        });

        $this->app->bind(OrderDraftUpdaterServiceInterface::class, function () {
            return new OrderDraftUpdaterService(
                new BaseOrderUpdaterService(
                    load_service(OrderDraftManagerInterface::class),
                    load_service(OrderCreationRestrictionByManufacturerCheckerInterface::class),
                    load_service(OrderSellerCheckerInterface::class),
                    load_service(OrderFinalPriceCheckerInterface::class),
                    UpdateOrderDTO::class
                )
            );
        });

        $this->app->bind(OrderActivityServiceInterface::class, function () {
            return new OrderActivityService(
                load_service(OrderRepositoryInterface::class),
                load_service(CommentRepositoryInterface::class),
            );
        });

        $this->app->bind(OrderCreationRestrictionByManufacturerCheckerInterface::class, function () {
            return new OrderCreationRestrictionByManufacturerChecker(
                load_service(DateLimitRepositoryInterface::class),
                load_service(ManufacturerRepositoryInterface::class),
                load_service(OrderRepositoryInterface::class),
            );
        });

        $this->app->bind(OrderStateCheckerInterface::class, OrderStateChecker::class);
        $this->app->bind(OrderExportServiceInterface::class, OrderExportService::class);

        $this->app->bind(OrderNumberGeneratorServiceInterface::class, function () {
            return new OrderNumberGeneratorService(load_service(OrderRepositoryInterface::class));
        });

        $this->app->bind(OrderFinalPriceProcessorInterface::class, function () {
            return new OrderFinalPriceProcessor(
                load_service(DooglysOrderSyncServiceInterface::class),
                load_service(OrderStateCheckerInterface::class)
            );
        });

        $this->app->bind(OrderFindWithCommentServiceInterface::class, function () {
            return new OrderFindWithCommentService(
                load_service(OrderRepositoryInterface::class),
                load_service(CommentRepositoryInterface::class)
            );
        });

        $this->app->bind(OrderStatusesRetrieverInterface::class, OrderStatusesRetriever::class);
        $this->app->bind(OrderFinalPriceCheckerInterface::class, OrderFinalPriceChecker::class);

        $this->app->bind(OrderExtendAllWithTotalCommentsServiceInterface::class, function () {
            return new OrderExtendAllWithTotalCommentsService(load_service(CommentRepositoryInterface::class));
        });

        $this->app->bind(DatabaseNotificationFormatterInterface::class, DatabaseNotificationFormatter::class);

        $this->app->bind(OrderSellerCheckerInterface::class, function () {
            return new OrderSellerChecker(load_service(SellerRepositoryInterface::class));
        });

        $this->app->bind(OrderContactTypeRetrieverInterface::class, OrderContactTypeRetriever::class);

        $this->app->bind(OrderSettingTypeRetrieverInterface::class, OrderSettingTypeRetriever::class);
    }
}