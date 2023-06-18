<?php

declare(strict_types=1);

namespace App\Managers\Order;

use App\DTOs\Order\CreateOrderDTOInterface;
use App\DTOs\Order\OrderDraftDTOInterface;
use App\DTOs\Order\UpdateOrderDTOInterface;
use App\Models\Order\Detail\OrderDetail;
use App\Models\Order\OrderInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractOrderManager implements OrderManagerInterface
{
    public function __construct(private Builder $builder)
    {
    }

    // TODO: refactor arguments
    public function create(CreateOrderDTOInterface|OrderDraftDTOInterface $orderDTO): Model
    {
        [$orderAttributes, $orderDetailAttributes] = $this->prepareAttributes($orderDTO->toArray());

        /** @var OrderInterface|Model $order */
        $order = $this->builder->create($orderAttributes);

        if ($orderDTO->files()) {
            $fileIds = collect($orderDTO->files())
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

    // TODO: refactor arguments
    public function update(int $id, UpdateOrderDTOInterface|OrderDraftDTOInterface $orderDTO): ?Model
    {
        /** @var OrderInterface|Model $order */
        if (!$order = $this->builder->find($id)) {
            return null;
        }

        [$orderAttributes, $orderDetailAttributes] = $this->prepareAttributes($orderDTO->toArray());

        $order->update($orderAttributes);

        if ($orderDTO->files()) {
            $fileIds = collect($orderDTO->files())
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
        if (!$order = $this->builder->find($id)) {
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
            ->partition(function (?string $value, string $key) use ($detailAttributes): bool {
                return !isset($detailAttributes[$key]);
            })
            ->toArray();
    }
}