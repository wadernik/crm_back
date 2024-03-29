<?php

declare(strict_types=1);

namespace App\Managers\Order;

use App\DTOs\Order\CreateOrderDTOInterface;
use App\DTOs\Order\UpdateOrderDTOInterface;
use App\Models\Order\Contact\OrderContact;
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

        $this->manageItems($order, $orderDTO->items());

        $this->manageContact($order, $orderDTO->contact());

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

        $this->manageItems($order, $orderDTO->items());

        $this->manageContact($order, $orderDTO->contact());

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

    public function remove(Order $order): Order
    {
        $order->forceDelete();

        return $order;
    }

    private function manageItems(Order $order, array $requestItems = []): void
    {
        if (!$requestItems) {
            return;
        }

        $orderItems = $order->items->keyBy('id');

        $toCreate = [];
        $toUpdate = [];
        $toDelete = clone $orderItems;

        foreach ($requestItems as $item) {
            if (empty($item['id'])) {
                $toCreate[] = $item;

                continue;
            }

            $toUpdate[$item['id']] = $item;
        }

        foreach ($toCreate as $item) {
            $item['order_id'] = $order->id;

            /** @var OrderItem $orderItem */
            $orderItem = OrderItem::query()->create($item);

            if (!empty($item['files'])) {
                $fileIds = collect($item['files'])
                    ->pluck('id')
                    ->toArray();

                $orderItem->files()->sync($fileIds);
            }
        }

        foreach ($toUpdate as $itemId => $item) {
            if (!$orderItems->has($item['id'])) {
                continue;
            }

            $toDelete->forget($itemId);

            /** @var OrderItem $orderItem */
            $orderItem = $orderItems->get($itemId);

            $orderItem->update($item);

            if (!empty($item['files'])) {
                $fileIds = collect($item['files'])
                    ->pluck('id')
                    ->toArray();

                $orderItem->files()->sync($fileIds);
            }
        }

        foreach ($toDelete as $item) {
            $item->delete();
        }
    }

    private function manageContact(Order $order, array $requestContact = []): void
    {
        if (!$requestContact) {
            return;
        }

        $requestContact['order_id'] = $order->id;

        $existingContact = $order->contact;

        if (!$existingContact) {
            OrderContact::query()->create($requestContact);

            return;
        }

        $existingContact->update($requestContact);
    }
}