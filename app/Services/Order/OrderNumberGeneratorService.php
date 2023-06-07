<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Repositories\Order\OrderRepositoryInterface;
use Carbon\Carbon;

final class OrderNumberGeneratorService implements OrderNumberGeneratorServiceInterface
{
    private OrderRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = app(OrderRepositoryInterface::class);
    }


    public function generate(): string
    {
        $nowCarbon = Carbon::now();

        $criteria = [
            'filter' => [
                'order_date_start' => $nowCarbon->startOfMonth()->format('Y-m-d'),
                'order_date_end' => $nowCarbon->endOfMonth()->format('Y-m-d'),
            ],
        ];

        $ordersAmount = $this->repository->count($criteria) + 1;

        $ordersAmountFormatted = sprintf("%02d", $ordersAmount);

        return $ordersAmountFormatted . $nowCarbon->format('m');
    }
}