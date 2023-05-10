<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface FindOneByIdInterface
{
    public function find(int $id): ?Model;
}