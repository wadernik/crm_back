<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Sub\AbstractRepositoryInterface;
use App\Repositories\Sub\CountInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractRepository implements AbstractRepositoryInterface, CountInterface
{
    private Builder $builder;
    private Model $model;

    public function __construct(private readonly string $modelClass)
    {
        $this->model = new $this->modelClass();

        $this->builder = $this->model::query();
    }

    abstract public function addExtraFilter(Builder $builder, array &$criteria): void;

    public function find(int $id): ?Model
    {
        return $this->builder->find($id);
    }

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
    ): Collection
    {
        $this->applyFilter($criteria);

        $this->applySort($sort);

        $this->applyLimit($limit);

        $this->applyOffset($limit, $offset);

        $results = $this->builder->get($attributes);

        $this->reset();

        return $results;
    }

    public function applyFilter(array $criteria): void
    {
        if (!isset($criteria['filter'])) {
            return;
        }

        $this->addExtraFilter($this->builder, $criteria);

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

    public function applyLimit(?string $limit = null): void
    {
        if ($limit) {
            $limit = (int) $limit;

            $this->builder->limit($limit);
        }
    }

    public function applyOffset(?string $limit = null, ?string $offset = null): void
    {
        if ($offset && $limit) {
            $limit = (int) $limit;
            $offset = (int) $offset;

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

    private function reset(): void
    {
        $this->builder = $this->model::query();
    }
}