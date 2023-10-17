<?php

declare(strict_types=1);

namespace App\Repositories\Board\Board;

use App\Models\Board\Board;

interface FindOneByIdInterface
{
    public function find(int $id): ?Board;
}