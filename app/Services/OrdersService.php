<?php

namespace App\Services;

use App\ModelFilters\OrdersFilter;
use App\Models\Order;
use App\Services\Traits\Filterable;

class OrdersService
{
    use Filterable;

    /**
     * @param int $id
     * @param array|string[] $attributes
     * @param array $with
     * @return array
     */
    public function getOrder(int $id, array $attributes = ['*'], array $with = []): array
    {
        $order = Order::query()->find($id, $attributes);

        return $order ? $order->load($with)->toArray() : [];
    }

    /**
     * @param array|string[] $attributes
     * @param array $requestParams
     * @param array $with
     * @return array
     */
    public function getOrders(array $attributes = ['*'], array $requestParams = [], array $with = []): array
    {
        $ordersQuery = Order::query();

        $this->applyFilterParams($ordersQuery, $requestParams, OrdersFilter::class);
        $this->applyPageParams($ordersQuery, $requestParams);

        return $ordersQuery
            ->get($attributes)
            ->load($with)
            ->makeVisible($attributes)
            ->toArray();
    }

    /**
     * @param array $filterParams
     * @return int
     */
    public function countOrders(array $filterParams = []): int
    {
        $ordersQuery = Order::query();

        $this->applyFilterParams($ordersQuery, $filterParams, OrdersFilter::class);

        return $ordersQuery->count('id');
    }

    /**
     * Retrieve order statuses with captions
     * @return array
     */
    public function getStatuses(): array
    {
        return collect(Order::statusCaptions())
            ->map(function (string $statusCaption, int $status) {
                return [
                    'id' => $status,
                    'name' => $statusCaption,
                ];
            })
            ->values()
            ->toArray();
    }

    public function createOrder()
    {
    }

    public function processOrder()
    {
    }

    public function approveOrder()
    {
    }

    /**
     * Manufacturers can fulfill a limited amount of orders per day.
     * Function checks if it can take an order for a specific date.
     * @param string $orderDate
     * @param array $manufacturer`
     * @return bool
     */
    public function canCreateOrder(string $orderDate, array $manufacturer): bool
    {
        $filterParams = [
            'filter' => [
                'manufacturer_id' => $manufacturer['id'],
                'order_date' => $orderDate,
            ],
        ];

        $ordersAmount = $this->countOrders($filterParams);

        return $ordersAmount >= $manufacturer['limit'];
    }

    private function generateOrderNumber()
    {
    }
}
