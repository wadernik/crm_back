<?php

declare(strict_types=1);

namespace App\Repositories\Sub;

interface ApplyFilterInterface
{
    public function applyFilter(array $criteria): void;
}