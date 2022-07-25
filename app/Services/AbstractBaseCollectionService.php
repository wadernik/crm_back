<?php

namespace App\Services;

use App\ModelModifiers\ModelFilters\AbstractBaseModelFilter;
use App\ModelModifiers\ModelSorts\AbstractBaseModelSort;
use App\Services\Traits\FilterableTrait;
use App\Services\Traits\JoinableTrait;
use App\Services\Traits\PaginationableTrait;
use App\Services\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractBaseCollectionService
{
    use FilterableTrait;
    use JoinableTrait;
    use PaginationableTrait;
    use SortableTrait;

    protected Model $modelClass;
    protected AbstractBaseModelFilter $modelFilter;
    protected AbstractBaseModelSort $modelSort;
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

    /**
     * @param array $tables
     */
    public function setJoins(array $tables): void
    {
        $this->joins[] = $tables;
    }
}
