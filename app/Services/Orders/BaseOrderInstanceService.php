<?php

namespace App\Services\Orders;

use App\Models\OrderDetail;
use App\Services\BaseInstanceService;
use Illuminate\Database\Eloquent\Model;

abstract class BaseOrderInstanceService extends BaseInstanceService
{
    /**
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
            'name' => $attributes['name'] ?? null,
            'amount' => $attributes['amount'] ?? null,
            'label' => $attributes['label'] ?? null,
            'comment' => $attributes['comment'] ?? null,
        ]);

        OrderDetail::query()->create($orderDetailsAttributes);

        // TODO check if files do actually exist before syncing; may cause problems
        if (isset($params['file_ids'])) {
            $orderInstance->files()->sync($params['file_ids']);
        }

        return $orderInstance;
    }
}
