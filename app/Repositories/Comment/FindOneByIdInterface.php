<?php

declare(strict_types=1);

namespace App\Repositories\Comment;

use App\Models\Comment\Comment;

interface FindOneByIdInterface
{
    public function find(int $id): ?Comment;
}