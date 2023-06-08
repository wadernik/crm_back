<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Sub\AbstractRepositoryInterface;
use App\Repositories\Sub\CountInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

abstract class AbstractRepository implements AbstractRepositoryInterface, CountInterface
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

    public function applyFilter(array $criteria): void
    {
        if (!isset($criteria['filter'])) {
            return;
        }

        foreach ($criteria['filter'] as $key => $criterion) {
            if (is_array($criterion)) {
                $this->builder->whereIn($key, $criterion);
            } else {
                $this->builder->where($key, $criterion);
            }
        }
    }

    public function applySort(array $sort = []): void
    {
        $sortParams = [
            'sort' => 'id',
            'order' => 'asc',
        ];

        if (isset($sort['sort'])) {
            $sortParams = [
                'sort' => $sort['sort'],
                'order' => (($sort['order'] ?? 'asc') === 'desc' ? 'desc' : 'asc'),
            ];
        }

        $this->builder->orderBy($sortParams['sort'], $sortParams['order']);
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

    public function applyWith(array $with): void
    {
        $this->builder->with($with);
    }

    public function withTrashed(): void
    {
        $this->builder->withTrashed();
    }

    public function count(array $criteria): int
    {
        $this->applyFilter($criteria);

        return $this->builder->count();
    }
}