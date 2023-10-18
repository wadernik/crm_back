<?php

declare(strict_types=1);

namespace App\Managers\Order;

use App\DTOs\Order\CreateOrderDTOInterface;
use App\DTOs\Order\UpdateOrderDTOInterface;
use App\Models\Order\Item\OrderItem;
use App\Models\Order\Order;
use App\Models\Order\OrderInterface;
use Carbon\Carbon;
use function activity;
use function collect;

final class BaseOrderManager implements BaseOrderManagerInterface
{
    public function __construct(private readonly bool $draft = false)
    {
    }

    public function create(CreateOrderDTOInterface $orderDTO): Order
    {
        $mainAttributes = $orderDTO->main();
        $mainAttributes['draft'] = $this->draft;

        if ($this->draft) {
            activity()->disableLogging();
        }

        /** @var OrderInterface|Order $order */
        $order = Order::query()->create($mainAttributes);

        if ($orderDTO->files()) {
            $fileIds = collect($orderDTO->files())
                ->pluck('id')
                ->toArray();

            $order->files()->sync($fileIds);
        }

        foreach ($orderDTO->items() as $item) {
            $item['order_id'] = $order->id;

            OrderItem::query()
                ->where('order_id', $order->id)
                ->create($item);
        }

        if ($this->draft) {
            activity()->enableLogging();
        }

        return $order;
    }

    public function update(Order $order, UpdateOrderDTOInterface $orderDTO): Order
    {
        $mainAttributes = $orderDTO->main();
        $mainAttributes['draft'] = $this->draft;

        if ($this->draft) {
            activity()->disableLogging();
        }

        $order->update($mainAttributes);

        if ($orderDTO->files()) {
            $fileIds = collect($orderDTO->files())
                ->pluck('id')
                ->toArray();

            $order->files()->sync($fileIds);
        }

        $items = collect($orderDTO->items())->keyBy('id');

        $itemIds = $items
            ->keys()
            ->toArray();

        $orderItems = OrderItem::query()
            ->whereIn('id', $itemIds)
            ->get();

        foreach ($orderItems as $orderItem) {
            if ($orderItem->order_id !== $order->id) {
                continue;
            }

            $orderItem->update($items->get($orderItem->id));
        }

        $order->update(['updated_at' => Carbon::now()->timestamp]);

        if ($this->draft) {
            activity()->enableLogging();
        }

        return $order;
    }

    public function delete(Order $order): Order
    {
        $order->delete();

        return $order;
    }
}