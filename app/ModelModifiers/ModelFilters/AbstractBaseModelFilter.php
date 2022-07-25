<?php

namespace App\ModelModifiers\ModelFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class AbstractBaseModelFilter
{
    protected Builder $builder;

    /**
     * @param Builder $builder
     * @param array $filterParams
     */
    public function apply(Builder $builder, array $filterParams): void
    {
        $this->builder = $builder;

        foreach ($filterParams as $name => $value) {
            if (!is_string($name)) {
                $name = $value; $value = null;
            }

            $method = $this->getFilterMethod($name);

            if (!method_exists($this, $method)) {
                Log::warning(__CLASS__ . ": method [$method] not defined");
                continue;
            }

            $this->$method($value);
        }
    }

    /**
     * @param string $columnName
     * @return string
     */
    public function getColumnName(string $columnName): string
    {
        return $this->builder->qualifyColumn($columnName);
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getFilterMethod(string $name): string
    {
        return 'filter' . Str::studly(preg_replace('/\W/', '', $name));
    }
}
