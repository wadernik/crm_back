<?php

declare(strict_types=1);

namespace App\Repositories\Comment;

use App\Models\Comment\Comment;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class CommentRepository extends AbstractRepository implements CommentRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Comment::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
    }

    public function find(int $id): ?Model
    {
        return Comment::query()->find($id);
    }
}