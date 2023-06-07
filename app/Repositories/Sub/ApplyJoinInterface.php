<?php

declare(strict_types=1);

namespace App\Repositories\Sub;

interface ApplyJoinInterface
{
    public function join(string $table, string $first, string $operator, string $second): void;
}