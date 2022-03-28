<?php

namespace App\Services\Traits;

use App\ModelFilters\BaseModelFilter;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * @param array $params
     * @return array
     */
    public function createFilterParams(array $params): array
    {
        $filterParams = [];

        if (!empty($params['filter'])) {
            $filterParams = $params['filter'];
        }

        return $filterParams;
    }

    /**
     * @param array $params
     * @param array|string[] $default
     * @return array
     */
    public function createSortParams(array $params, array $default = ['id' => 'asc']): array
    {
        $sortParams = $default;

        if (!empty($params['sort'])) {
            $sortParams = [
                $params['sort'] => (($params['order'] ?? 'asc') === 'desc' ? 'desc' : 'asc'),
            ];
        }

        return $sortParams;
    }

    /**
     * @param array $params
     * @return array
     */
    public function createPageParams(array $params): array
    {
        $pageParams = [];

        if (!empty($params['limit']) && !empty($params['page'])) {
            $pageParams = [
                'limit' => (int)$params['limit'],
                'page' => (int)$params['page'],
            ];
        }

        return $pageParams;
    }

    public function applyFilterParams(Builder $builder, array $params, string $filterClass): void
    {
        $filterParams = $this->createFilterParams($params);
        $builder->filter($filterClass, $filterParams);
    }

    /**
     * @param Builder $builder
     * @param array $params
     */
    public function applyPageParams(Builder $builder, array $params): void
    {
        $pageParams = $this->createPageParams($params);

        if (!empty($pageParams)) {
            $builder
                ->offset($pageParams['limit'] * ($pageParams['page'] - 1))
                ->limit($pageParams['limit']);
        }
    }
}
