<?php

declare(strict_types=1);

namespace App\Managers\Manufacturer;

use App\DTOs\Manufacturer\CreateManufacturerDTOInterface;
use App\DTOs\Manufacturer\UpdateManufacturerDTOInterface;
use App\Models\Manufacturer\Manufacturer;

final class ManufacturerManager implements ManufacturerManagerInterface
{
    public function create(CreateManufacturerDTOInterface $manufacturerDTO): Manufacturer
    {
        /** @var Manufacturer $manufacturer */
        $manufacturer = Manufacturer::query()->create($manufacturerDTO->toArray());

        return $manufacturer;
    }

    public function update(int $id, UpdateManufacturerDTOInterface $manufacturerDTO): ?Manufacturer
    {
        /** @var Manufacturer $manufacturer */
        if (!$manufacturer = Manufacturer::query()->find($id)) {
            return null;
        }

        $manufacturer->update($manufacturerDTO->toArray());

        return $manufacturer;
    }

    public function delete(int $id): ?Manufacturer
    {
        /** @var Manufacturer $manufacturer */
        if (!$manufacturer = Manufacturer::query()->find($id)) {
            return null;
        }

        $manufacturer->delete();

        return $manufacturer;
    }
}