<?php

declare(strict_types=1);

namespace App\Services\Order\Checker;

use App\Models\Manufacturer\ManufacturerDateLimit;
use App\Repositories\ManufacturerDateLimit\DateLimitRepositoryInterface;
use function app;

final class OrderCreationRestrictionChecker implements OrderCreationRestrictionCheckerInterface
{
    public function __construct(private DateLimitRepositoryInterface $repository)
    {
        $this->repository = app(DateLimitRepositoryInterface::class);
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

        return $this->repository->findAllBy($criteria)->isEmpty();
    }
}