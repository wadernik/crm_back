<?php

declare(strict_types=1);

namespace App\Managers\ManufacturerDateLimit;

use App\DTOs\ManufacturerDateLimit\CreateDateLimitDTOInterface;
use App\DTOs\ManufacturerDateLimit\UpdateDateLimitDTOInterface;
use App\Models\Manufacturer\ManufacturerDateLimit;

final class DateLimitManager implements DateLimitManagerInterface
{
    public function create(CreateDateLimitDTOInterface $dateLimitDTO): ManufacturerDateLimit
    {
        /** @var ManufacturerDateLimit $dateLimit */
        $dateLimit = ManufacturerDateLimit::query()->create($dateLimitDTO->toArray());

        return $dateLimit;
    }

    public function update(int $id, UpdateDateLimitDTOInterface $dateLimitDTO): ?ManufacturerDateLimit
    {
        /** @var ManufacturerDateLimit $dateLimit */
        if (!$dateLimit = ManufacturerDateLimit::query()->find($id)) {
            return null;
        }

        $dateLimit->update($dateLimitDTO->toArray());

        return $dateLimit;
    }

    public function delete(int $id): ?ManufacturerDateLimit
    {
        /** @var ManufacturerDateLimit $dateLimit */
        if (!$dateLimit = ManufacturerDateLimit::query()->find($id)) {
            return null;
        }

        $dateLimit->delete();

        return $dateLimit;
    }
}