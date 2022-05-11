<?php

namespace App\ModelModifiers\ModelSorts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class AbstractBaseModelSort
{
    protected Builder $builder;

    /**
     * @param Builder $builder
     * @param array $sortParams
     */
    public function apply(Builder $builder, array $sortParams): void
    {
        $this->builder = $builder;

        foreach ($sortParams as $name => $value) {
            $method = $this->getSortMethod($name);

            if (!method_exists($this, $method)) {
                Log::warning(__CLASS__ . ": method [{$method}] not defined");
                continue;
            }

            $this->$method($value);
        }
    }

    /**
     * @param string $name
     * @return string
     */
    private function getSortMethod(string $name): string
    {
        return 'sort' . Str::studly(preg_replace('/\W/', '', $name));
    }
}
