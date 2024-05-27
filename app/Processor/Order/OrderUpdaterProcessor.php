<?php

declare(strict_types=1);

namespace App\Processor\Order;

use App\DTOs\Order\Composite\OrderComposite;
use App\DTOs\Order\Composite\OrderCompositeInterface;
use App\DTOs\Order\OrderDTO;
use App\Events\Order\OrderEntityEvent;
use App\Events\Order\OrderEntityEventTypeEnum;
use App\Managers\OrderComposite\OrderCompositeUpdaterInterface;
use App\Models\Order\Order;

final class OrderUpdaterProcessor implements OrderUpdaterProcessorInterface
{
    public function __construct(private readonly OrderCompositeUpdaterInterface $manager)
    {
    }

    public function process(Order $order, array $request): OrderCompositeInterface
    {
        $request['draft'] = false;

        $dto = new OrderDTO($request);

        $orderComposite = new OrderComposite($dto);

        $order->fill($orderComposite->order()->toArray());

        $orderComposite->setOrder($order);

        $this->manager->update($orderComposite);

        OrderEntityEvent::dispatch($orderComposite->order(), OrderEntityEventTypeEnum::UPDATED);

        return $orderComposite;
    }
}