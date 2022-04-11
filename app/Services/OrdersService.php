<?php

namespace App\Services;

use App\ModelFilters\OrdersFilter;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\Traits\Filterable;
use Carbon\Carbon;

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

    public function createOrder(array $params): ?int
    {
        // Create an Order instance
        $orderAttributes = [
            'manufacturer_id' => $params['manufacturer_id'],
            'source_id' => $params['source_id'],
            'seller_id' => $params['seller_id'],
            'number' => $this->generateOrderNumber(),
            'status' => Order::STATUS_ACCEPTED,
            'product_code' => $params['product_code'] ?? '',
            'accepted_date' => $params['accepted_date'],
            'order_date' => $params['order_date'],
            'order_time' => $params['order_time'],
        ];

        $order = Order::query()->create($orderAttributes);

        if (!$orderId = $order['id']) {
            return null;
        }

        // Create an OrderDetails instance
        $orderDetailsAttributes = array_filter([
            'order_id' => $orderId,
            'name' => $params['name'],
            'amount' => $params['amount'],
            'label' => $params['label'],
            'comment' => $params['comment'],
        ]);

        OrderDetail::query()->create($orderDetailsAttributes);

        // Create files relations
        // TODO check if files do actually exist before syncing; may cause problems
        if (isset($params['file_ids'])) {
            $order->files()->sync($params['file_ids']);
        }

        return $orderId;
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

        return $ordersAmount <= $manufacturer['limit'];
    }

    /**
     * return string
     */
    public function generateOrderNumber(): string
    {
        $nowCarbon = Carbon::now();

        $filterParams = [
            'filter' => [
                'order_date' => $nowCarbon->format('Y-m-d'),
            ],
        ];

        $ordersAmount = $this->countOrders($filterParams) + 1;
        $ordersAmountFormatted = sprintf("%02d", $ordersAmount);

        return $ordersAmountFormatted . $nowCarbon->format('m');
    }
}
