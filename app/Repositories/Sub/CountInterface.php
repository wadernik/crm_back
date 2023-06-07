<?php

declare(strict_types=1);

namespace App\Repositories\Sub;

interface CountInterface
{
    public function count(array $criteria): int;
}