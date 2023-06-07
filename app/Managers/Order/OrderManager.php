<?php

declare(strict_types=1);

namespace App\Managers\Order;

use App\DTOs\Order\CreateOrderDTOInterface;
use App\DTOs\Order\UpdateOrderDTOInterface;
use App\Models\Order\Detail\OrderDetail;
use App\Models\Order\Order;
use App\Models\Order\OrderInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

final class OrderManager implements OrderManagerInterface
{
    public function create(CreateOrderDTOInterface $orderDTO): Model
    {
        [$orderAttributes, $orderDetailAttributes] = $this->prepareAttributes($orderDTO->toArray());

        /** @var OrderInterface|Model $order */
        $order = Order::query()->create($orderAttributes);

        if (isset($attributes['files'])) {
            $fileIds = collect($attributes['files'])
                ->pluck('id')
                ->toArray();

            $order->files()->sync($fileIds);
        }

        if ($orderDetailAttributes) {
            $orderDetailAttributes['order_id'] = $order->id;

            OrderDetail::query()
                ->where('order_id', $order->id)
                ->create($orderDetailAttributes);
        }

        return $order;
    }

    public function update(int $id, UpdateOrderDTOInterface $orderDTO): ?Model
    {
        /** @var OrderInterface|Model $order */
        if (!$order = Order::query()->find($id)) {
            return null;
        }

        [$orderAttributes, $orderDetailAttributes] = $this->prepareAttributes($orderDTO->toArray());

        $order->update($orderAttributes);

        if (isset($attributes['files'])) {
            $fileIds = collect($attributes['files'])
                ->pluck('id')
                ->toArray();

            $order->files()->sync($fileIds);
        }

        if ($orderDetailAttributes) {
            $orderDetailAttributes['order_id'] = $order->id;

            OrderDetail::query()
                ->where('order_id', $order->id)
                ->get()
                ->first()
                ->update($orderDetailAttributes);

            $order->update(['updated_at' => Carbon::now()->timestamp]);
        }

        return $order;
    }

    public function delete(int $id): ?Model
    {
        if (!$order = Order::query()->find($id)) {
            return null;
        }

        $order->delete();

        return $order;
    }

    private function prepareAttributes(array $attributes): array
    {
        $detailAttributes = [
            'name' => true,
            'amount' => true,
            'label' => 'true',
            'decoration' => true,
            'comment' => true
        ];

        return collect($attributes)
            ->partition(function (string $value, string $key) use ($detailAttributes): bool {
                return !isset($detailAttributes[$key]);
            })
            ->toArray();
    }
}