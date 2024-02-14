<?php

declare(strict_types=1);

namespace App\Services\Order\OrderNumber;

use App\Repositories\Order\OrderRepositoryInterface;
use Carbon\Carbon;
use function sprintf;

final class OrderNumberGeneratorService implements OrderNumberGeneratorServiceInterface
{
    public function __construct(private readonly OrderRepositoryInterface $repository)
    {
    }

    public function generate(string $orderDate): string
    {
        $nowCarbon = Carbon::parse($orderDate);

        $criteria = [
            'filter' => [
                'order_date_start' => $nowCarbon->startOfMonth()->format('Y-m-d'),
                'order_date_end' => $nowCarbon->endOfMonth()->format('Y-m-d'),
                'with_trashed' => true,
            ],
        ];

        $ordersAmount = $this->repository->count($criteria) + 1;

        $ordersAmountFormatted = sprintf("%02d", $ordersAmount);

        return $ordersAmountFormatted . $nowCarbon->format('m');
    }
}