<?php

declare(strict_types=1);

namespace App\Services\Order\Delivery;

use App\DTOs\Order\OrderCounterFilterDTO;
use App\DTOs\Order\OrderCounterResponseDTO;
use App\Repositories\Order\OrderRepositoryInterface;

use function array_filter;

final class OrderCounterService
{
    public function __construct(private readonly OrderRepositoryInterface $orderRepository)
    {
    }

    public function countByFilter(OrderCounterFilterDTO $filterDTO): OrderCounterResponseDTO
    {
        $withDeliveriesFilterData = array_filter([
            'delivery_date_start' => $filterDTO->getDateStart(),
            'delivery_date_end' => $filterDTO->getDateEnd(),
            'has_delivery' => true,
        ]);

        $totalWithDeliveries = $this->orderRepository->count(['filter' => $withDeliveriesFilterData]);

        $withoutDeliveriesFilterData = array_filter([
            'order_date_start' => $filterDTO->getDateStart(),
            'order_date_end' => $filterDTO->getDateEnd(),
            'has_delivery' => false,
        ]);

        $totalWithoutDeliveries = $this->orderRepository->count(['filter' => $withoutDeliveriesFilterData]);

        $withDeliveriesFilterData = array_filter([
            'delivery_date_start' => $filterDTO->getDateStart(),
            'delivery_date_end' => $filterDTO->getDateEnd(),
            'has_delivery' => true,
            'courier_id' => $filterDTO->getUserId(),
        ]);

        $totalWithDeliveriesByUser = $this->orderRepository->count(['filter' => $withDeliveriesFilterData]);

        return new OrderCounterResponseDTO($totalWithoutDeliveries, $totalWithDeliveries, $totalWithDeliveriesByUser);
    }
}