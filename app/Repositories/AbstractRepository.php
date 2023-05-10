<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

abstract class AbstractRepository implements FindAllByCriteriaInterface, CountInterface
{
    public function __construct(private Builder $builder)
    {
    }

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
    ): Collection
    {
        $this->applyFilter($criteria);

        $this->applySort($sort);

        $this->applyLimit($limit);

        $this->applyOffset($limit, $offset);

        return $this->builder->get($attributes);
    }

    public function count(array $criteria): int
    {
        $this->applyFilter($criteria);

        return $this->builder->count();
    }

    public function applyFilter(array $criteria): void
    {
        if (!isset($criteria['filter'])) {
            return;
        }

        foreach ($criteria['filter'] as $key => $criterion) {
            // TODO: think about this
            if ($key === 'ids') {
                $this->builder->whereIn('id', $criterion);
            } else {
                $this->builder->where($key, $criterion);
            }
        }
    }

    public function applySort(array $sort = []): void
    {
        if (!$sort) {
            $sort = [
                'sort' => 'id',
                'order' => 'desc',
            ];
        }

        $this->builder->orderBy($sort['sort'], $sort['order']);
    }

    public function applyLimit(?int $limit = null): void
    {
        if ($limit) {
            $this->builder->limit($limit);
        }
    }

    public function applyOffset(?int $limit = null, ?int $offset = null): void
    {
        if ($offset && $limit) {
            $this->builder->offset($limit * ($offset - 1));
        }
    }
}