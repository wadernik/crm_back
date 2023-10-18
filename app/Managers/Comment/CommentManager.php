<?php

declare(strict_types=1);

namespace App\Managers\Comment;

use App\DTOs\Comment\UpdateCommentDTOInterface;
use App\Models\Comment\Comment;

final class CommentManager implements CommentManagerInterface
{
    public function update(int $id, UpdateCommentDTOInterface $commentDTO): ?Comment
    {
        /** @var Comment $comment */
        if (!$comment = Comment::query()->find($id)) {
            return null;
        }

        $comment->update(['comment' => $commentDTO->comment()]);

        return $comment;
    }

    public function delete(int $id): ?Comment
    {
        /** @var Comment $comment */
        if (!$comment = Comment::query()->find($id)) {
            return null;
        }

        $comment->delete();

        return $comment;
    }
}