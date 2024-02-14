<?php

declare(strict_types=1);

namespace App\Services\Order\Checker;

use App\Models\Manufacturer\ManufacturerDateLimit;
use App\Repositories\Manufacturer\ManufacturerRepositoryInterface;
use App\Repositories\ManufacturerDateLimit\DateLimitRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;

final class OrderCreationRestrictionByManufacturerChecker implements
    OrderCreationRestrictionByManufacturerCheckerInterface
{
    public function __construct(
        private readonly DateLimitRepositoryInterface $dateLimitRepository,
        private readonly ManufacturerRepositoryInterface $manufacturerRepository,
        private readonly OrderRepositoryInterface $orderRepository,
    )
    {
    }

    public function check(
        ?int $manufacturerId = null,
        ?string $orderDate = null,
        int $limitType = ManufacturerDateLimit::STATUS_FULL_STOP
    ): bool
    {
        // If date and manufacturer were not passed here, than they were not updated.
        // This way we don't need to check for any existing restriction and can skip everything ahead.
        if (!$orderDate || !$manufacturerId) {
            return true;
        }

        $criteria = ['filter' => [
            'manufacturer_id' => $manufacturerId,
            'date' => $orderDate,
            'limit_type' => $limitType
        ]];

        // If there is no restriction for current date, we skip everything else
        if ($this->dateLimitRepository->findAllBy($criteria)->isEmpty()) {
            return true;
        }

        if (!$manufacturer = $this->manufacturerRepository->find($manufacturerId)) {
            return false;
        }

        $criteria = ['filter' => [
            'manufacturer_id' => $manufacturerId,
            'order_date' => $orderDate,
        ]];

        $ordersAmount = $this->orderRepository->count($criteria);

        return $ordersAmount < $manufacturer->limit;
    }
}