<?php

namespace App\Services;

use App\ModelFilters\OrdersFilter;
use App\Models\BaseOrder;
use App\Models\BaseOrderDetail;
use App\Services\Traits\Filterable;
use Carbon\Carbon;

abstract class BaseOrdersService
{
    use Filterable;

    protected BaseOrder $orderClass;
    protected BaseOrderDetail $orderDetailClass;

    /**
     * @param int $id
     * @param array|string[] $attributes
     * @param array $with
     * @return array
     */
    protected function getOrder(int $id, array $attributes = ['*'], array $with = []): array
    {
        $order = $this->orderClass::query()->find($id, $attributes);

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
        $ordersQuery = $this->orderClass::query();

        $this->applyFilterParams($ordersQuery, $requestParams, OrdersFilter::class);
        $this->applyPageParams($ordersQuery, $requestParams);

        return $ordersQuery
            ->get($attributes)
            ->load($with)
            ->makeVisible($attributes)
            ->toArray();
    }

    /**
     * @param array $params
     * @return int
     */
    public function countOrders(array $params = []): int
    {
        $ordersQuery = $this->orderClass::query();

        $this->applyFilterParams($ordersQuery, $params, OrdersFilter::class);

        return $ordersQuery->count('id');
    }

    /**
     * Retrieve order statuses with captions
     * @return array
     */
    public function getStatuses(): array
    {
        return collect($this->orderClass::statusCaptions())
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
        $orderAttributes = array_filter([
            'manufacturer_id' => $params['manufacturer_id'],
            'source_id' => $params['source_id'],
            'seller_id' => $params['seller_id'],
            'number' => $this->generateOrderNumber(),
            'status' => BaseOrder::STATUS_ACCEPTED,
            'product_code' => $params['product_code'] ?? '',
            'accepted_date' => $params['accepted_date'],
            'order_date' => $params['order_date'],
            'order_time' => $params['order_time'],
        ]);

        $orderInstance = $this->orderClass::query()->create($orderAttributes);

        if (!$orderInstanceId = ($orderInstance['id'] ?? null)) {
            return null;
        }

        $orderDetailsAttributes = array_filter([
            'order_id' => $orderInstanceId,
            'name' => $params['name'],
            'amount' => $params['amount'],
            'label' => $params['label'],
            'comment' => $params['comment'],
        ]);

        $this->orderDetailClass::query()->create($orderDetailsAttributes);

        // TODO check if files do actually exist before syncing; may cause problems
        if (isset($params['file_ids'])) {
            $orderInstance->files()->sync($params['file_ids']);
        }

        return $orderInstanceId;
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
        $params = [
            'filter' => [
                'manufacturer_id' => $manufacturer['id'],
                'order_date' => $orderDate,
            ],
        ];

        $ordersAmount = $this->countOrders($params);

        return $ordersAmount <= $manufacturer['limit'];
    }

    /**
     * return string
     */
    public function generateOrderNumber(): string
    {
        $nowCarbon = Carbon::now();

        $params = [
            'filter' => [
                'order_date' => $nowCarbon->format('Y-m-d'),
            ],
        ];

        $ordersAmount = $this->countOrders($params) + 1;
        $ordersAmountFormatted = sprintf("%02d", $ordersAmount);

        return $ordersAmountFormatted . $nowCarbon->format('m');
    }
}
