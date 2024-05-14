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
                'created_at_start' => $nowCarbon->startOfMonth()->format('Y-m-d'),
                'created_at_end' => $nowCarbon->endOfMonth()->format('Y-m-d'),
                'with_trashed' => true,
                'ignore_draft' => true,
            ],
        ];

        $ordersAmount = $this->repository->count($criteria) + 1;

        $ordersAmountFormatted = sprintf("%02d", $ordersAmount);

        return $ordersAmountFormatted . $nowCarbon->format('m');
    }
}