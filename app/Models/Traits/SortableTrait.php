<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SortableTrait
{
    public function scopeSort(Builder $builder, string $sortClass, array $params): void
    {
        (new $sortClass())->apply($builder, $params);
    }
}