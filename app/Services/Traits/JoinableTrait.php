<?php

namespace App\Services\Traits;

use Illuminate\Database\Eloquent\Builder;

trait JoinableTrait
{
    /**
     * @param Builder $builder
     * @param array $tables
     */
    public function joinTables(Builder $builder, array $tables = []): void
    {
        if (empty($tables)) {
            return;
        }

        foreach ($tables as $tableParams) {
            $table = $tableParams['table'];
            $first = $tableParams['first'];
            $operator = $tableParams['operator'];
            $second = $tableParams['second'];

            $builder->join($table, $first, $operator, $second);
        }
    }
}
