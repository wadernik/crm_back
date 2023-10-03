<?php

declare(strict_types=1);

namespace App\Managers\Order;

use App\DTOs\Order\CreateOrderDTOInterface;
use App\DTOs\Order\UpdateOrderDTOInterface;
use App\Models\Order\Item\OrderItem;
use App\Models\Order\Order;
use App\Models\Order\OrderInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use function collect;

abstract class AbstractOrderManager implements AbstractOrderManagerInterface
{
    public function __construct(private readonly bool $draft)
    {
    }

    public function create(CreateOrderDTOInterface $orderDTO): Model
    {
        $mainAttributes = $orderDTO->main();
        $mainAttributes['draft'] = $this->draft;

        /** @var OrderInterface|Order $order */
        $order = Order::query()->create($mainAttributes);

        if ($this->draft) {
            $order->disableLogging();
        }

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

        return $order;
    }

    public function update(Order $order, UpdateOrderDTOInterface $orderDTO): Model
    {
        $mainAttributes = $orderDTO->main();
        $mainAttributes['draft'] = $this->draft;

        if ($this->draft) {
            $order->disableLogging();
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

        return $order;
    }

    public function delete(Order $order): Order
    {
        $order->delete();

        return $order;
    }
}