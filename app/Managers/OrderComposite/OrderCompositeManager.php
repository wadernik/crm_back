<?php

declare(strict_types=1);

namespace App\Managers\OrderComposite;

use App\DTOs\Order\Composite\OrderCompositeInterface;
use App\Exceptions\OrderException;
use App\Models\Order\Contact\OrderContact;
use App\Models\Order\Delivery\OrderDelivery;
use App\Models\Order\Item\OrderItem;
use App\Models\Order\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use function activity;

final class OrderCompositeManager implements OrderCompositeManagerInterface
{
    public function create(OrderCompositeInterface $orderComposite): void
    {
        DB::beginTransaction();

        $order = $orderComposite->order();

        if ($order->draft) {
            activity()->disableLogging();
        }

        try {
            $order->save();

            $this->manageItems($order, $orderComposite->items());

            $this->manageContact($order, $orderComposite->contact());

            $this->manageDelivery($order, $orderComposite->delivery());
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());

            DB::rollBack();

            throw new OrderException();
        }

        DB::commit();

        if ($order->draft) {
            activity()->enableLogging();
        }
    }

    public function update(OrderCompositeInterface $orderComposite): void
    {
        DB::beginTransaction();

        $order = $orderComposite->order();

        if ($order->draft) {
            activity()->disableLogging();
        }

        try {
            $order->update();

            $this->manageItems($order, $orderComposite->items());

            $this->manageContact($order, $orderComposite->contact());

            $this->manageDelivery($order, $orderComposite->delivery());
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());

            DB::rollBack();

            throw new OrderException();
        }

        DB::commit();

        if ($order->draft) {
            activity()->enableLogging();
        }
    }

    public function delete(Order $order): void
    {
        DB::beginTransaction();

        try {
            $items = $order->items;

            foreach ($items as $item) {
                $item->files()->delete();

                $item->delete();
            }

            $order->contact->delete();

            if ($order->delivery) {
                $order->delivery->delete();
            }

            $order->delete();
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());

            DB::rollBack();

            throw new OrderException();
        }

        DB::commit();
    }

    /**
     * @param Order $order
     * @param array<OrderItem> $items
     *
     * @return void
     */
    private function manageItems(Order $order, array $items): void
    {
        if (empty($items)) {
            return;
        }

        $existingItems = $order->items->keyBy('id');

        $toCreate = [];
        $toUpdate = [];
        $toDelete = clone $existingItems;

        foreach ($items as $item) {
            if (!$item->id) {
                $toCreate[] = $item;

                continue;
            }

            $toUpdate[$item->id] = $item;
        }

        foreach ($toCreate as $item) {
            $item->order_id = $order->id;

            $item->save();

            if ($item->getFilesCollection()) {
                $item->files()->sync($item->getFilesCollection());
            }
        }

        foreach ($toUpdate as $item) {
            if (!$existingItems->has($item->id)) {
                continue;
            }

            $toDelete->forget($item->id);

            /** @var OrderItem $existingItem */
            $existingItem = $existingItems->get($item->id);

            $existingItem->update($item->toArray());

            if ($item->getFilesCollection()) {
                $existingItem->files()->sync($item->getFilesCollection());
            }
        }

        foreach ($toDelete as $item) {
            $item->delete();
        }
    }

    private function manageContact(Order $order, OrderContact $contact): void
    {
        if (empty($contact->toArray())) {
            return;
        }

        $contact->order_id = $order->id;

        $existingContact = $order->contact;

        if (!$existingContact) {
            $contact->save();

            return;
        }

        $existingContact->update($contact->toArray());
    }

    private function manageDelivery(Order $order, ?OrderDelivery $delivery = null): void
    {
        $existingDelivery = $order->delivery;

        if (!$delivery && $existingDelivery) {
            $existingDelivery->delete();

            return;
        }

        if (!$delivery) {
            return;
        }

        $delivery->order_id = $order->id;

        if (!$existingDelivery) {
            $delivery->save();

            return;
        }

        $existingDelivery->update($delivery->toArray());
    }
}