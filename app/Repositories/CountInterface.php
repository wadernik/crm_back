<?php

declare(strict_types=1);

namespace App\Repositories;

interface CountInterface
{
    public function count(array $criteria): int;
}