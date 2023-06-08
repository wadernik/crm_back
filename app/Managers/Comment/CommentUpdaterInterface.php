<?php

declare(strict_types=1);

namespace App\Managers\Comment;

use App\DTOs\Comment\UpdateCommentDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface CommentUpdaterInterface
{
    public function update(int $id, UpdateCommentDTOInterface $commentDTO): ?Model;
}