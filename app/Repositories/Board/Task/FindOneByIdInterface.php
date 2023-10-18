<?php

declare(strict_types=1);

namespace App\Repositories\Board\Task;

use App\Models\Board\Task;

interface FindOneByIdInterface
{
    public function find(int $id): ?Task;
}