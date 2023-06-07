<?php

declare(strict_types=1);

namespace App\Services\DateLimit;

use App\Models\Manufacturer\ManufacturerDateLimit;
use App\Repositories\ManufacturerDateLimit\DateLimitRepositoryInterface;

final class AcceptOrderValidatorService implements AcceptOrderValidatorServiceInterface
{
    public function __construct(private DateLimitRepositoryInterface $repository)
    {
        $this->repository = app(DateLimitRepositoryInterface::class);
    }

    public function canAccept(
        int $manufacturerId,
        string $orderDate,
        int $limitType = ManufacturerDateLimit::STATUS_FULL_STOP
    ): bool
    {
        $criteria = ['filter' => [
            'manufacturer_id' => $manufacturerId,
            'date' => $orderDate,
            'limit_type' => $limitType
        ]];

        return $this->repository->findAllBy($criteria)->isEmpty();
    }
}