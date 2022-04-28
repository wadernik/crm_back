<?php

namespace App\Services\Traits;

use Illuminate\Database\Eloquent\Builder;

trait PaginationableTrait
{
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
