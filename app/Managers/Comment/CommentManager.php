<?php

declare(strict_types=1);

namespace App\Managers\Comment;

use App\DTOs\Comment\UpdateCommentDTOInterface;
use App\Models\Comment\CustomComments;
use Illuminate\Database\Eloquent\Model;

final class CommentManager implements CommentManagerInterface
{
    public function update(int $id, UpdateCommentDTOInterface $commentDTO): ?Model
    {
        if (!$comment = CustomComments::query()->find($id)) {
            return null;
        }

        $comment->update(['comment' => $commentDTO->comment()]);

        return $comment;
    }

    public function delete(int $id): ?Model
    {
        if (!$comment = CustomComments::query()->find($id)) {
            return null;
        }

        $comment->delete();

        return $comment;
    }
}