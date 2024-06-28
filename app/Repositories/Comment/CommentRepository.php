<?php

declare(strict_types=1);

namespace App\Repositories\Comment;

use App\Models\Comment\Comment;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class CommentRepository extends AbstractRepository implements CommentRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Comment::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
    }

    public function find(int $id): ?Comment
    {
        /** @var Comment $comment */
        $comment = Comment::query()->find($id);

        return $comment;
    }

    public function aggregateByCommentableIds(string $commentableType, array $orderIds): Collection
    {
        return Comment::query()
            ->selectRaw('commentable_id, count(*) as amount')
            ->whereMorphedTo('commentable', $commentableType)
            ->whereIn('commentable_id', $orderIds)
            ->groupBy('commentable_id')
            ->get()
            ->keyBy('commentable_id');
    }
}