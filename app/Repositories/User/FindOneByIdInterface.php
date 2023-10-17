<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User\User;

interface FindOneByIdInterface
{
    public function find(int $id): ?User;
}