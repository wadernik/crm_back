<?php

declare(strict_types=1);

namespace App\Processor\Order;

use App\DTOs\Order\Composite\OrderComposite;
use App\DTOs\Order\Composite\OrderCompositeInterface;
use App\DTOs\Order\OrderDTO;
use App\Managers\OrderComposite\OrderCompositeUpdaterInterface;
use App\Models\Order\Order;
use App\Processor\Delivery\OrderDeliveryCourierSetterProcessor;

final class OrderDraftUpdaterProcessor implements OrderDraftUpdaterProcessorInterface
{
    public function __construct(
        private readonly OrderCompositeUpdaterInterface $manager,
        private readonly OrderDeliveryCourierSetterProcessor $courierSetterProcessor,
    )
    {
    }

    public function process(Order $order, array $request): OrderCompositeInterface
    {
        $request['draft'] = true;

        $dto = new OrderDTO($request);

        $orderComposite = new OrderComposite($dto);

        $order->fill($orderComposite->order()->toArray());

        $orderComposite->setOrder($order);

        $this->courierSetterProcessor->addCourierToDeliveryIfNotSet($orderComposite);

        $this->manager->update($orderComposite);

        return $orderComposite;
    }
}