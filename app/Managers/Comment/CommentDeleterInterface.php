<?php

declare(strict_types=1);

namespace App\Managers\Comment;

use App\Models\Comment\Comment;

interface CommentDeleterInterface
{
    public function delete(int $id): ?Comment;
}