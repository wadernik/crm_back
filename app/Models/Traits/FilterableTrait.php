<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterableTrait
{
    public function scopeFilter(Builder $builder, string $filterClass, array $params): void
    {
        (new $filterClass())->apply($builder, $params);
    }
}
