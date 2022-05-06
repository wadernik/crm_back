<?php

namespace App\Services\Orders;

use App\Models\OrderDetail;
use App\Services\BaseInstanceService;
use Carbon\Carbon;
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

        if (isset($attributes['files'])) {
            $fileIds = collect($attributes['files'])
                ->pluck('id')
                ->toArray();
            $orderInstance->files()->sync($fileIds);
        }

        if (!empty($orderDetailsAttributes)) {
            $orderDetailsAttributes['order_id'] = $orderInstance['id'];
            OrderDetail::query()
                ->where('order_id', $orderDetailsAttributes['order_id'])
                ->create($orderDetailsAttributes);
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
        if (!$order = $this->modelClass::query()->find($id)) {
            return null;
        }

        [$orderAttributes, $orderDetailsAttributes] = $this->prepareAttributes($attributes);

        $order->update($orderAttributes);

        if (isset($attributes['files'])) {
            $fileIds = collect($attributes['files'])
                ->pluck('id')
                ->toArray();
            $order->files()->sync($fileIds);
        }

        if (!empty($orderDetailsAttributes)) {
            $orderDetailsAttributes['order_id'] = $order['id'];
            OrderDetail::query()
                ->where('order_id', $orderDetailsAttributes['order_id'])
                ->update($orderDetailsAttributes);

            $order->update(['updated_at' => Carbon::now()->timestamp]);
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
            'status' => $attributes['status'] ?? null,
            'product_code' => $attributes['product_code'] ?? null,
            'accepted_date' => $attributes['accepted_date'] ?? null,
            'order_date' => $attributes['order_date'] ?? null,
            'order_time' => $attributes['order_time'] ?? null,
        ]);

        $orderDetailsAttributes = array_filter([
            'name' => $attributes['name'] ?? null,
            'amount' => $attributes['amount'] ?? null,
            'label' => $attributes['label'] ?? null,
            'comment' => $attributes['comment'] ?? null,
        ]);

        return [$orderAttributes, $orderDetailsAttributes];
    }
}
