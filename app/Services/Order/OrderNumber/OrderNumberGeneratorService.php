<?php

declare(strict_types=1);

namespace App\Services\Order\OrderNumber;

use App\Repositories\Order\OrderRepositoryInterface;
use Carbon\Carbon;
use function sprintf;
use function substr;

final class OrderNumberGeneratorService implements OrderNumberGeneratorServiceInterface
{
    public function __construct(private readonly OrderRepositoryInterface $repository)
    {
    }

    public function generate(string $orderDate): string
    {
        $orderDateCarbon = Carbon::parse($orderDate);

        $lastOrder = $this->repository->findLastOrderByOrderDate($orderDate);

        $orderCount = 1;

        if ($lastOrder) {
            $orderCount = (int) (substr($lastOrder->number, 0, -2)) + 1;
        }

        $ordersAmountFormatted = sprintf("%02d", $orderCount);

        return $ordersAmountFormatted . $orderDateCarbon->format('m');
    }
}