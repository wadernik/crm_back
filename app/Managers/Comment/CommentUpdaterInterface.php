<?php

declare(strict_types=1);

namespace App\Managers\Comment;

use App\DTOs\Comment\UpdateCommentDTOInterface;
use App\Models\Comment\Comment;

interface CommentUpdaterInterface
{
    public function update(Comment $comment, UpdateCommentDTOInterface $commentDTO): Comment;
}