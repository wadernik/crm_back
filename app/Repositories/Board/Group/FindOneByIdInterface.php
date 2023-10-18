<?php

declare(strict_types=1);

namespace App\Repositories\Board\Group;

use App\Models\Board\Group;

interface FindOneByIdInterface
{
    public function find(int $id): ?Group;
}