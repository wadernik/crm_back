<?php

namespace App\Services\Orders;

use App\Models\BaseOrder;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrderInstanceService extends BaseOrderInstanceService
{
    public function __construct(Order $order, OrderDetail $orderDetail)
    {
        $this->modelClass = $order;
        $this->orderDetailClass = $orderDetail;
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function createInstance(array $attributes): Model
    {
        $order = parent::createInstance($attributes);

        $order->update([
            'status' => BaseOrder::STATUS_ACCEPTED,
            'number' => $this->generateOrderNumber(),
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

        $ordersAmount = app(OrdersCollectionService::class)->countOrders($params);

        return $ordersAmount <= $manufacturer['limit'];
    }

    /**
     * return string
     */
    private function generateOrderNumber(): string
    {
        $nowCarbon = Carbon::now();

        $params = [
            'filter' => [
                'order_date' => $nowCarbon->format('Y-m-d'),
            ],
        ];

        $ordersAmount = app(OrdersCollectionService::class)->countOrders($params) + 1;
        $ordersAmountFormatted = sprintf("%02d", $ordersAmount);

        return $ordersAmountFormatted . $nowCarbon->format('m');
    }
}
