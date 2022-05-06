<?php

namespace App\Services;

use App\ModelModifiers\ModelFilters\BaseModelFilter;
use App\ModelModifiers\ModelSorts\BaseModelSort;
use App\Services\Traits\FilterableTrait;
use App\Services\Traits\JoinableTrait;
use App\Services\Traits\PaginationableTrait;
use App\Services\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

abstract class BaseCollectionService
{
    use FilterableTrait;
    use JoinableTrait;
    use PaginationableTrait;
    use SortableTrait;

    protected Model $modelClass;
    protected BaseModelFilter $modelFilter;
    protected BaseModelSort $modelSort;
    protected array $joins = [];

    /**
     * @param array|string[] $attributes
     * @param array $requestParams
     * @param array $with
     * @return array
     */
    public function getInstances(array $attributes = ['*'], array $requestParams = [], array $with = []): array
    {
        $query = $this->modelClass::query();

        $this->applyFilterParams($query, $requestParams, $this->modelFilter::class);
        $this->applyPageParams($query, $requestParams);
        $this->applySortParams($query, $requestParams, $this->modelSort::class);
        $this->joinTables($query, $this->joins);

        return $query
            ->get($attributes)
            ->load($with)
            ->toArray();
    }

    /**
     * @param array $requestParams
     * @return int
     */
    public function countInstances(array $requestParams = []): int
    {
        $query = $this->modelClass::query();

        $this->applyFilterParams($query, $requestParams, $this->modelFilter::class);
        $this->joinTables($query, $this->joins);

        return $query->count();
    }
}
