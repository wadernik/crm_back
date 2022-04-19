<?php

namespace App\Services;

use App\ModelFilters\BaseModelFilter;
use App\Services\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

abstract class BaseCollectionService
{
    use Filterable;

    protected Model $modelClass;
    protected BaseModelFilter $modelFilter;

    /**
     * @param array|string[] $attributes
     * @param array $requestParams
     * @param array $with
     * @return array
     */
    public function getInstances(array $attributes = ['*'], array $requestParams = [], array $with = []): array
    {
        $sellersQuery = $this->modelClass::query();

        $this->applyFilterParams($sellersQuery, $requestParams, $this->modelFilter::class);
        $this->applyPageParams($sellersQuery, $requestParams);

        return $sellersQuery
            ->get($attributes)
            ->load($with)
            ->toArray();
    }
}
