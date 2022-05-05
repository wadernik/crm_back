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
        [$orderAttributes, $orderDetailsAttributes] = $this->prepareAttributes($attributes);

        $orderInstance = $this->modelClass::query()->create($orderAttributes);

        $orderDetailsAttributes['order_id'] = $orderInstance['id'];

        OrderDetail::query()->create($orderDetailsAttributes);

        if (isset($attributes['files'])) {
            $fileIds = collect($attributes['files'])
                ->pluck('id')
                ->toArray();
            $orderInstance->files()->sync($fileIds);
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
        [$orderAttributes, $orderDetailsAttributes] = $this->prepareAttributes($attributes);

        if (!$order = $this->modelClass::query()->find($id)) {
            return null;
        }

        $orderDetailsAttributes['order_id'] = $order['id'];

        $order->update($orderAttributes);

        $orderDetail = OrderDetail::query()
            ->where('order_id', $order['id'])
            ->first();

        if (!$orderDetail) {
            return null;
        }

        $orderDetail->update($orderDetailsAttributes);

        if (isset($attributes['files'])) {
            $fileIds = collect($attributes['files'])
                ->pluck('id')
                ->toArray();
            $order->files()->sync($fileIds);
        }

        return $order;
    }

    /**
     * @param array $attributes
     * @return array [$orderAttributes, $orderDetailsAttributes]
     */
    private function prepareAttributes(array $attributes): array
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

        $orderDetailsAttributes = array_filter([
            'name' => $attributes['details']['name'] ?? null,
            'amount' => $attributes['details']['amount'] ?? null,
            'label' => $attributes['details']['label'] ?? null,
            'comment' => $attributes['details']['comment'] ?? null,
        ]);

        return [$orderAttributes, $orderDetailsAttributes];
    }
}
