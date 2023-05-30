<?php

declare(strict_types=1);

namespace App\Managers\Manufacturer;

use App\DTOs\Manufacturer\CreateManufacturerDTOInterface;
use App\DTOs\Manufacturer\UpdateManufacturerDTOInterface;
use App\Models\Manufacturer\Manufacturer;
use Illuminate\Database\Eloquent\Model;

final class ManufacturerManager implements ManufacturerManagerInterface
{
    public function create(CreateManufacturerDTOInterface $manufacturerDTO): Model
    {
        return Manufacturer::query()->create($manufacturerDTO->toArray());
    }

    public function update(int $id, UpdateManufacturerDTOInterface $manufacturerDTO): ?Model
    {
        if (!$manufacturer = Manufacturer::query()->find($id)) {
            return null;
        }

        $manufacturer->update($manufacturerDTO->toArray());

        return $manufacturer;
    }

    public function delete(int $id): ?Model
    {
        if (!$manufacturer = Manufacturer::query()->find($id)) {
            return null;
        }

        $manufacturer->delete();

        return $manufacturer;
    }
}