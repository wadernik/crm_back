<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Collection;

interface FindAllByIdsInterface
{
    /**
     * @param int ...$ids
     *
     * @return Collection
     */
    public function findByIds(int ...$ids): Collection;
}