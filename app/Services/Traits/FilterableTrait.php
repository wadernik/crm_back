<?php

namespace App\Services\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterableTrait
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
     * @param Builder $builder
     * @param array $params
     * @param string $filterClass
     */
    public function applyFilterParams(Builder $builder, array $params, string $filterClass): void
    {
        $filterParams = $this->createFilterParams($params);
        $builder->filter($filterClass, $filterParams);
    }
}
