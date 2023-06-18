<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Models\Order\BaseOrder;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class AbstractOrderRepository implements OrderRepositoryInterface
{
    private OrderFilterInterface $filter;

    private Model $model;
    private Builder $builder;

    /**
     * @param string $modelClass
     * @param string $filterClass
     *
     * @throws BindingResolutionException
     */
    public function __construct(private string $modelClass, private string $filterClass)
    {
        $this->model = new $this->modelClass();

        $this->builder = $this->model::query();

        $this->join(
            'order_details',
            'order_details.order_id',
            '=',
            'orders.id'
        );

        $this->filter = app()->make($this->filterClass, ['builder' => $this->builder]);
    }

    public function join(string $table, string $first, string $operator, string $second): void
    {
        $this->builder->join($table, $first, $operator, $second);
    }

    public function applyWith(array $with): void
    {
        $this->builder->with($with);
    }

    public function findAllBy(
        array $criteria,
        array $attributes = ['*'],
        array $sort = [],
        ?string $limit = null,
        ?string $offset = null
    ): Collection
    {
        $this->applyFilter($criteria);

        $sortParams = [
            'sort' => 'orders.id',
            'order' => 'asc',
        ];

        if (isset($sort['sort'])) {
            $sortParams = [
                'sort' => $sort['sort'],
                'order' => (($sort['order'] ?? 'asc') === 'desc' ? 'desc' : 'asc'),
            ];
        }

        $this->builder->orderBy($sortParams['sort'], $sortParams['order']);

        if ($limit) {
            $this->builder->limit($limit);
        }

        if ($offset && $limit) {
            $this->builder->offset($limit * ($offset - 1));
        }

        $results = $this->builder->get($attributes);

        $this->reset();

        return $results;
    }

    public function find(int $id): ?Model
    {
        return $this->builder->find($id);
    }

    public function count(array $criteria): int
    {
        $this->applyFilter($criteria);

        return $this->builder->count();
    }

    public function statuses(): array
    {
        return collect(BaseOrder::statusCaptions())
            ->map(function (string $statusCaption, int $status) {
                return [
                    'id' => $status,
                    'name' => $statusCaption,
                ];
            })
            ->values()
            ->toArray();
    }

    private function applyFilter(array $criteria): void
    {
        if (!isset($criteria['filter'])) {
            return;
        }

        foreach ($criteria['filter'] as $field => $value) {
            if (!is_string($field)) {
                $field = $value; $value = null;
            }

            $method = $this->getFilterMethod($field);

            if (!method_exists($this->filter, $method)) {
                continue;
            }

            $this->filter->$method($value);
        }
    }

    private function getFilterMethod(string $name): string
    {
        return 'filter' . Str::studly(preg_replace('/\W/', '', $name));
    }

    private function reset(): void
    {
        $this->builder = $this->model::query();
    }
}