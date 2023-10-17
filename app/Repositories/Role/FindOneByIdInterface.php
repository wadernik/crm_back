<?php

declare(strict_types=1);

namespace App\Repositories\Role;

use App\Models\Role\Role;

interface FindOneByIdInterface
{
    public function find(int $id): ?Role;
}