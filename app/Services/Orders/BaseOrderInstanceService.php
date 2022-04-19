<?php

namespace App\Services\Orders;

use App\Models\BaseOrderDetail;
use App\Services\BaseInstanceService;
use Illuminate\Database\Eloquent\Model;

abstract class BaseOrderInstanceService extends BaseInstanceService
{
    protected BaseOrderDetail $orderDetailClass;

    /**
     * @param array $attributes
     * @return Model
     */
    public function createInstance(array $attributes): Model
    {
        $orderAttributes = array_filter([
            'manufacturer_id' => $attributes['manufacturer_id'],
            'source_id' => $attributes['source_id'],
            'seller_id' => $attributes['seller_id'],
            'product_code' => $attributes['product_code'] ?? '',
            'accepted_date' => $attributes['accepted_date'],
            'order_date' => $attributes['order_date'],
            'order_time' => $attributes['order_time'],
        ]);

        $orderInstance = $this->modelClass::query()->create($orderAttributes);

        $orderDetailsAttributes = array_filter([
            'order_id' => $orderInstance['id'],
            'name' => $attributes['name'],
            'amount' => $attributes['amount'],
            'label' => $attributes['label'],
            'comment' => $attributes['comment'],
        ]);

        $this->orderDetailClass::query()->create($orderDetailsAttributes);

        // TODO check if files do actually exist before syncing; may cause problems
        if (isset($params['file_ids'])) {
            $orderInstance->files()->sync($params['file_ids']);
        }

        return $orderInstance;
    }
}
