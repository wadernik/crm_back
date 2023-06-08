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
     * @param string|null $limit
     * @param string|null $offset
     *
     * @return Collection
     */
    public function findAllBy(
        array $criteria,
        array $attributes = ['*'],
        array $sort = [],
        ?string $limit = null,
        ?string $offset = null
    ): Collection;
}