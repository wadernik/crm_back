<?php

namespace App\Providers\Order;

use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\OrderDraftDTO;
use App\DTOs\Order\UpdateOrderDTO;
use App\Formatters\Notification\DatabaseNotificationFormatter;
use App\Formatters\Notification\DatabaseNotificationFormatterInterface;
use App\Managers\Order\Draft\OrderDraftManagerInterface;
use App\Managers\Order\Normal\OrderManagerInterface;
use App\Managers\OrderComposite\OrderCompositeManagerInterface;
use App\Processor\Order\OrderCreatorProcessor;
use App\Processor\Order\OrderCreatorProcessorInterface;
use App\Processor\Order\OrderDraftCreatorProcessor;
use App\Processor\Order\OrderDraftCreatorProcessorInterface;
use App\Processor\Order\OrderDraftUpdaterProcessor;
use App\Processor\Order\OrderDraftUpdaterProcessorInterface;
use App\Processor\Order\OrderUpdaterProcessor;
use App\Processor\Order\OrderUpdaterProcessorInterface;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Manufacturer\ManufacturerRepositoryInterface;
use App\Repositories\ManufacturerDateLimit\DateLimitRepositoryInterface;
use App\Repositories\Order\File\OrderFileRepositoryInterface;
use App\Repositories\Order\Filter\OrderFilter;
use App\Repositories\Order\Filter\OrderFilterInterface;
use App\Repositories\Order\Filter\OrderFilterProcessor;
use App\Repositories\Order\Filter\OrderFilterProcessorInterface;
use App\Repositories\Order\OrderDraftRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Seller\SellerRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
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
use App\Services\Order\Enricher\OrderCompositeByCommentsEnricher;
use App\Services\Order\Enricher\OrderCompositeByCommentsEnricherInterface;
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
use App\Services\Order\Processor\OrderInspectorProcessor;
use App\Services\Order\Processor\OrderInspectorProcessorInterface;
use App\Services\Order\Product\DeleteOrderProductByRequestService;
use App\Services\Order\Product\DeleteOrderProductByRequestServiceInterface;
use App\Services\Order\Product\RestoreOrderProductByRequestService;
use App\Services\Order\Product\RestoreOrderProductByRequestServiceInterface;
use App\Services\Order\Status\OrderStatusesRetriever;
use App\Services\Order\Status\OrderStatusesRetrieverInterface;
use App\Services\Setting\ManagerExtension\SettingCreatorService;
use App\Services\Setting\ManagerExtension\SettingCreatorServiceInterface;
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
                    load_service(OrderNumberGeneratorServiceInterface::class),
                    load_service(OrderInspectorProcessorInterface::class),
                    CreateOrderDTO::class
                ),
                load_service(OrderSellerCheckerInterface::class),
                load_service(OrderDraftRepositoryInterface::class)
            );
        });

        $this->app->bind(OrderDraftCreatorServiceInterface::class, function () {
            return new OrderDraftCreatorService(
                new BaseOrderCreatorService(
                    load_service(OrderDraftManagerInterface::class),
                    load_service(OrderCreationRestrictionByManufacturerCheckerInterface::class),
                    load_service(OrderNumberGeneratorServiceInterface::class),
                    load_service(OrderInspectorProcessorInterface::class),
                    OrderDraftDTO::class
                )
            );
        });

        $this->app->bind(OrderUpdaterServiceInterface::class, function () {
            return new OrderUpdaterService(
                new BaseOrderUpdaterService(
                    load_service(OrderManagerInterface::class),
                    load_service(OrderCreationRestrictionByManufacturerCheckerInterface::class),
                    load_service(OrderFinalPriceCheckerInterface::class),
                    UpdateOrderDTO::class
                ),
                load_service(OrderSellerCheckerInterface::class),
            );
        });

        $this->app->bind(OrderDraftUpdaterServiceInterface::class, function () {
            return new OrderDraftUpdaterService(
                new BaseOrderUpdaterService(
                    load_service(OrderDraftManagerInterface::class),
                    load_service(OrderCreationRestrictionByManufacturerCheckerInterface::class),
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

        $this->app->bind(SettingCreatorServiceInterface::class, SettingCreatorService::class);

        $this->app->bind(OrderInspectorProcessorInterface::class, function () {
            return new OrderInspectorProcessor(load_service(UserRepositoryInterface::class));
        });

        // $this->app->bind(OrderCreatorProcessorInterface::class, function () {
        //     return new OrderCreatorProcessor(load_service(OrderCompositeManagerInterface::class));
        // });

        $this->app->bind(OrderCreatorProcessorInterface::class, OrderCreatorProcessor::class);

        $this->app->bind(OrderUpdaterProcessorInterface::class, function () {
            return new OrderUpdaterProcessor(load_service(OrderCompositeManagerInterface::class));
        });

        $this->app->bind(OrderCompositeByCommentsEnricherInterface::class, function () {
            return new OrderCompositeByCommentsEnricher(
                load_service(CommentRepositoryInterface::class),
                load_service(OrderFileRepositoryInterface::class)
            );
        });

        $this->app->bind(OrderDraftCreatorProcessorInterface::class, OrderDraftCreatorProcessor::class);

        $this->app->bind(OrderDraftUpdaterProcessorInterface::class, function () {
            return new OrderDraftUpdaterProcessor(load_service(OrderCompositeManagerInterface::class));
        });

        $this->app->bind(
            RestoreOrderProductByRequestServiceInterface::class,
            RestoreOrderProductByRequestService::class
        );

        $this->app->bind(
            DeleteOrderProductByRequestServiceInterface::class,
            DeleteOrderProductByRequestService::class
        );
    }
}