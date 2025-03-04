<?php

declare(strict_types=1);

namespace App\Processor\Order;

use App\DTOs\Order\Composite\OrderComposite;
use App\DTOs\Order\Composite\OrderCompositeInterface;
use App\DTOs\Order\OrderDTO;
use App\Managers\OrderComposite\OrderCompositeManagerInterface;
use App\Models\Order\OrderStatus;
use App\Processor\Delivery\OrderDeliveryCourierSetterProcessor;
use App\Services\Order\OrderNumber\OrderNumberGeneratorServiceInterface;
use App\Services\Order\Processor\OrderInspectorProcessorInterface;
use Illuminate\Support\Carbon;
use function auth;

final class OrderDraftCreatorProcessor implements OrderDraftCreatorProcessorInterface
{
    public function __construct(
        private readonly OrderCompositeManagerInterface $manager,
        private readonly OrderNumberGeneratorServiceInterface $numberGeneratorService,
        private readonly OrderInspectorProcessorInterface $orderInspectorProcessor,
        private readonly OrderDeliveryCourierSetterProcessor $courierSetterProcessor,
    )
    {
    }

    public function process(array $request): OrderCompositeInterface
    {
        $request['draft'] = true;

        if (!isset($request['user_id'])) {
            $request['user_id'] = auth('sanctum')->id();
        }

        if (!isset($requst['inspector_id'])) {
            $request['inspector_id'] = $this->orderInspectorProcessor->process();
        }

        $dto = new OrderDTO($request);

        $orderComposite = new OrderComposite($dto);

        $orderComposite->order()->status = OrderStatus::STATUS_CREATED;

        $orderComposite->order()->number = $this->numberGeneratorService->generate(
            $orderComposite->order()->order_date ?? Carbon::now()->startOfDay()->format('Y-m-d')
        );

        $this->courierSetterProcessor->addCourierToDeliveryIfNotSet($orderComposite);

        $this->manager->create($orderComposite);

        return $orderComposite;
    }
}