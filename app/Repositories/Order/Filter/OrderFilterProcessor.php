<?php

declare(strict_types=1);

namespace App\Repositories\Order\Filter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use function is_string;
use function method_exists;
use function preg_replace;

final class OrderFilterProcessor implements OrderFilterProcessorInterface
{
    public function filter(Builder $builder, array $criteria): void
    {
        $filter = app()->make(OrderFilterInterface::class, ['builder' => $builder]);

        if (!isset($criteria['filter'])) {
            return;
        }

        foreach ($criteria['filter'] as $field => $value) {
            if (!is_string($field)) {
                $field = $value; $value = null;
            }

            $method = $this->getFilterMethod($field);

            if (!method_exists($filter, $method)) {
                continue;
            }

            $filter->$method($value);
        }
    }

    private function getFilterMethod(string $name): string
    {
        return 'filter' . Str::studly(preg_replace('/\W/', '', $name));
    }
}