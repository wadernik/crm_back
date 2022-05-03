<?php

namespace App\Services\Orders;

use App\Models\OrderDetail;
use App\Services\BaseInstanceService;
use Illuminate\Database\Eloquent\Model;

abstract class BaseOrderInstanceService extends BaseInstanceService
{
    /**
     * TODO: подумать над этими методами
     * @param array $attributes
     * @return Model
     */
    public function createInstance(array $attributes): Model
    {
        $orderAttributes = array_filter([
            'manufacturer_id' => $attributes['manufacturer_id'] ?? null,
            'source_id' => $attributes['source_id'] ?? null,
            'seller_id' => $attributes['seller_id'] ?? null,
            'user_id' => $attributes['user_id'],
            'product_code' => $attributes['product_code'] ?? null,
            'accepted_date' => $attributes['accepted_date'] ?? null,
            'order_date' => $attributes['order_date'] ?? null,
            'order_time' => $attributes['order_time'] ?? null,
        ]);

        $orderInstance = $this->modelClass::query()->create($orderAttributes);

        $orderDetailsAttributes = array_filter([
            'order_id' => $orderInstance['id'],
            'name' => $attributes['details']['name'] ?? null,
            'amount' => $attributes['details']['amount'] ?? null,
            'label' => $attributes['details']['label'] ?? null,
            'comment' => $attributes['details']['comment'] ?? null,
        ]);

        OrderDetail::query()->create($orderDetailsAttributes);

        // TODO check if files do actually exist before syncing; may cause problems
        if (isset($attributes['file_ids'])) {
            $orderInstance->files()->sync($attributes['file_ids']);
        }

        return $orderInstance;
    }

    /**
     * @param int $id
     * @param array $attributes
     * @return Model|null
     */
    public function editInstance(int $id, array $attributes): ?Model
    {
        $orderAttributes = array_filter([
            'manufacturer_id' => $attributes['manufacturer_id'] ?? null,
            'source_id' => $attributes['source_id'] ?? null,
            'seller_id' => $attributes['seller_id'] ?? null,
            'user_id' => $attributes['user_id'],
            'product_code' => $attributes['product_code'] ?? null,
            'accepted_date' => $attributes['accepted_date'] ?? null,
            'order_date' => $attributes['order_date'] ?? null,
            'order_time' => $attributes['order_time'] ?? null,
        ]);

        if (!$order = $this->modelClass::query()->find($id)) {
            return null;
        }

        $order->update($orderAttributes);

        $orderDetailsAttributes = array_filter([
            'order_id' => $order['id'],
            'name' => $attributes['details']['name'] ?? null,
            'amount' => $attributes['details']['amount'] ?? null,
            'label' => $attributes['details']['label'] ?? null,
            'comment' => $attributes['details']['comment'] ?? null,
        ]);

        $orderDetail = OrderDetail::query()
            ->where('order_id', $order['id'])
            ->first();

        if (!$orderDetail) {
            return null;
        }

        $orderDetail->update($orderDetailsAttributes);

        // TODO check if files do actually exist before syncing; may cause problems
        if (isset($attributes['file_ids'])) {
            $order->files()->sync($attributes['file_ids']);
        }

        return $order;
    }
}
