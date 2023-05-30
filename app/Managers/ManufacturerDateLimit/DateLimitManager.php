<?php

declare(strict_types=1);

namespace App\Managers\ManufacturerDateLimit;

use App\DTOs\ManufacturerDateLimit\CreateDateLimitDTOInterface;
use App\DTOs\ManufacturerDateLimit\UpdateDateLimitDTOInterface;
use App\Models\Manufacturer\ManufacturerDateLimit;
use Illuminate\Database\Eloquent\Model;

final class DateLimitManager implements DateLimitManagerInterface
{
    public function create(CreateDateLimitDTOInterface $dateLimitDTO): Model
    {
        return ManufacturerDateLimit::query()->create($dateLimitDTO->toArray());
    }

    public function update(int $id, UpdateDateLimitDTOInterface $dateLimitDTO): ?Model
    {
        if (!$dateLimit = ManufacturerDateLimit::query()->find($id)) {
            return null;
        }

        $dateLimit->update($dateLimitDTO->toArray());

        return $dateLimit;
    }

    public function delete(int $id): ?Model
    {
        if (!$dateLimit = ManufacturerDateLimit::query()->find($id)) {
            return null;
        }

        $dateLimit->delete();

        return $dateLimit;
    }
}