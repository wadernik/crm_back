<?php

declare(strict_types=1);

namespace App\Repositories;

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

    public function applyFilter(array $criteria): void;

    public function applySort(array $sort = []): void;

    public function applyLimit(?int $limit = null): void;

    public function applyOffset(?int $limit = null, ?int $offset = null): void;
}