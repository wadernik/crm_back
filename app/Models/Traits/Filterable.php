<?php

namespace App\Models\Traits;

use App\ModelFilters\BaseModelFilter;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter(Builder $builder, string $filterClass, array $params): void
    {
        (new $filterClass())->apply($builder, $params);
    }
}
