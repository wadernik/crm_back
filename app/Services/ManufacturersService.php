<?php

namespace App\Services;

use App\Models\Manufacturer;

class ManufacturersService
{
    /**
     * @param int $manufacturerId
     * @param array|string[] $attributes
     * @return array
     */
    public function getManufacturer(int $manufacturerId, array $attributes = ['*']): array
    {
        $manufacturer = Manufacturer::query()->find($manufacturerId, $attributes);

        return $manufacturer ? $manufacturer->toArray() : [];
    }
}
