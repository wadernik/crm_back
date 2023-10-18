<?php

declare(strict_types=1);

namespace App\Managers\Comment;

use App\DTOs\Comment\UpdateCommentDTOInterface;
use App\Models\Comment\Comment;

final class CommentManager implements CommentManagerInterface
{
    public function update(Comment $comment, UpdateCommentDTOInterface $commentDTO): Comment
    {
        $comment->update(['comment' => $commentDTO->comment()]);

        return $comment;
    }

    public function delete(Comment $comment): Comment
    {
        $comment->delete();

        return $comment;
    }
}