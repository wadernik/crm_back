<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Managers\Order\OrderManagerInterface;
use App\Models\Order\BaseOrder;
use App\Models\Order\OrderInterface;
use App\Services\Order\Dooglys\DooglysServiceInterface;
use Illuminate\Database\Eloquent\Model;

final class OrderUpdaterService extends AbstractOrderUpdaterService
{
    private DooglysServiceInterface $dooglysService;

    public function __construct()
    {
        parent::__construct(OrderManagerInterface::class);

        $this->dooglysService = app(DooglysServiceInterface::class);
    }

    public function update(int $id, array $attributes): ?Model
    {
        /** @var ?Model|?OrderInterface $order */
        $order = parent::update($id, $attributes);

        if (!$order->number_external && $order->status !== BaseOrder::STATUS_SOLD) {
            return $order;
        }

        $finalPrice = $this->dooglysService->finalPrice(
            $order->created_at,
            $order->number_external
        );

        if ($finalPrice) {
            $order->update(['price' => $finalPrice]);
        }

        return $order;
    }
}