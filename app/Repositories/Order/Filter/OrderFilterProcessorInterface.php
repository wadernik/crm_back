<?php

declare(strict_types=1);

namespace App\Repositories\Order\Filter;

use Illuminate\Database\Eloquent\Builder;
use Throwable;

interface OrderFilterProcessorInterface
{
    /**
     * @param Builder $builder
     * @param array   $criteria
     *
     * @return void
     *
     * @throws Throwable
     */
    public function filter(Builder $builder, array $criteria): void;
}