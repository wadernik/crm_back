<?php

namespace App\Services;

use App\ModelFilters\ManufacturersFilter;
use App\Models\Manufacturer;
use App\Services\Traits\Filterable;

class ManufacturersService
{
    use Filterable;

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

    public function getManufacturers(array $attributes = ['*'], array $requestParams = []): array
    {
        $manufacturersQuery = Manufacturer::query();

        $this->applyFilterParams($manufacturersQuery, $requestParams, ManufacturersFilter::class);
        $this->applyPageParams($manufacturersQuery, $requestParams);

        return $manufacturersQuery
            ->get($attributes)
            ->toArray();
    }
}
