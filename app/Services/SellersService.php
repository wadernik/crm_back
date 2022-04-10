<?php

namespace App\Services;

use App\ModelFilters\SellersFilter;
use App\Models\Seller;
use App\Services\Traits\Filterable;

class SellersService
{
    use Filterable;

    /**
     * @param int $id
     * @param array|string[] $attributes
     * @return array
     */
    public function getSeller(int $id, array $attributes = ['*']): array
    {
        $seller = Seller::query()->find($id, $attributes);

        return $seller ? $seller->toArray() : [];
    }

    /**
     * @param array|string[] $attributes
     * @param array $requestParams
     * @return array
     */
    public function getSellers(array $attributes = ['*'], array $requestParams = []): array
    {
        $sellersQuery = Seller::query();

        $this->applyFilterParams($sellersQuery, $requestParams, SellersFilter::class);
        $this->applyPageParams($sellersQuery, $requestParams);

        return $sellersQuery
            ->get($attributes)
            ->toArray();
    }

    /**
     * Alias for getSeller function
     * @param int $id
     * @param array|string[] $attributes
     * @return array
     */
    public function getSource(int $id, array $attributes = ['*']): array
    {
        return $this->getSeller($id, $attributes);
    }

    /**
     * Alias for getSellers function
     * @param array|string[] $attributes
     * @param array $requestParams
     * @return array
     */
    public function getSources(array $attributes = ['*'], array $requestParams = []): array
    {
        return $this->getSellers($attributes, $requestParams);
    }
}
