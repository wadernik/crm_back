<?php

namespace App\Services\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SortableTrait
{
    /**
     * @param array $params
     * @return array
     */
    public function createSortParams(array $params): array
    {
        $sortParams = ['id' => 'asc'];

        if (!empty($params['sort'])) {
            $sortParams = [
                $params['sort'] => (($params['order'] ?? 'asc') === 'desc' ? 'desc' : 'asc'),
            ];
        }

        return $sortParams;
    }

    /**
     * @param Builder $builder
     * @param array $params
     * @param string $sortClass
     */
    public function applySortParams(Builder $builder, array $params, string $sortClass): void
    {
        $sortParams = $this->createSortParams($params);
        $builder->sort($sortClass, $sortParams);
    }
}
