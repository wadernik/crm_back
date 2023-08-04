<?php

declare(strict_types=1);

namespace App\Managers\Comment;

use App\DTOs\Comment\UpdateCommentDTOInterface;
use App\Models\Comment\Comment;
use Illuminate\Database\Eloquent\Model;

final class CommentManager implements CommentManagerInterface
{
    public function update(int $id, UpdateCommentDTOInterface $commentDTO): ?Model
    {
        if (!$comment = Comment::query()->find($id)) {
            return null;
        }

        $comment->update(['comment' => $commentDTO->comment()]);

        return $comment;
    }

    public function delete(int $id): ?Model
    {
        if (!$comment = Comment::query()->find($id)) {
            return null;
        }

        $comment->delete();

        return $comment;
    }
}