<?php

declare(strict_types=1);

namespace App\Repositories\Sub;

use Illuminate\Support\Collection;

interface FindAllByCriteriaInterface
{
    /**
     * @param array $criteria
     * @param array $attributes
     * @param array $sort
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return Collection
     */
    public function findAllBy(
        array $criteria,
        array $attributes = ['*'],
        array $sort = [],
        ?int $limit = null,
        ?int $offset = null
    ): Collection;
}