<?php

namespace App\Services\Orders;

use App\Models\BaseOrder;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrderInstanceService extends AbstractBaseOrderInstanceService
{
    public function __construct(Order $order)
    {
        $this->modelClass = $order;
        $this->joins = [
            [
                'table' => 'order_details',
                'first' => 'order_details.order_id',
                'operator' => '=',
                'second' => 'orders.id',
            ]
        ];
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function createInstance(array $attributes): Model
    {
        $orderNumber = $this->generateOrderNumber();
        $order = parent::createInstance($attributes);

        $order->update([
            'status' => BaseOrder::STATUS_ACCEPTED,
            'number' => $orderNumber,
        ]);

        return $order;
    }

    /**
     * @param int $orderId
     * @param int $status
     */
    public function setStatus(int $orderId, int $status): void
    {
        $order = Order::query()->find($orderId);

        if (!$order) {
            return;
        }

        $order->update(['status' => $status]);
    }

    /**
     * Manufacturers can fulfill a limited amount of orders per day.
     * Function checks if it can take an order for a specific date.
     * @param string $orderDate
     * @param array $manufacturer
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

        $ordersAmount = app(OrdersCollectionService::class)->countInstances($params);

        return $ordersAmount < $manufacturer['limit'];
    }

    /**
     * return string
     */
    private function generateOrderNumber(): string
    {
        $nowCarbon = Carbon::now();

        $params = [
            'filter' => [
                'order_date_start' => $nowCarbon->startOfMonth()->format('Y-m-d'),
                'order_date_end' => $nowCarbon->endOfMonth()->format('Y-m-d'),
            ],
        ];

        $ordersAmount = app(OrdersCollectionService::class)->countInstances($params) + 1;
        $ordersAmountFormatted = sprintf("%02d", $ordersAmount);

        return $ordersAmountFormatted . $nowCarbon->format('m');
    }
}
