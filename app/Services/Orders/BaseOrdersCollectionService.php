<?php

namespace App\Services\Orders;

use App\Models\BaseOrder;
use App\Models\BaseOrderDetail;
use App\Services\BaseCollectionService;

abstract class BaseOrdersCollectionService extends BaseCollectionService
{
    protected BaseOrderDetail $orderDetailClass;

    /**
     * Retrieve order statuses with captions
     * @return array
     */
    public function getStatuses(): array
    {
        return collect($this->modelClass::statusCaptions())
            ->map(function (string $statusCaption, int $status) {
                return [
                    'id' => $status,
                    'name' => $statusCaption,
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * @param array $params
     * @return int
     */
    public function countOrders(array $params = []): int
    {
        $ordersQuery = $this->modelClass::query();

        $this->applyFilterParams($ordersQuery, $params, $this->modelFilter::class);

        return $ordersQuery->count('id');
    }
}
