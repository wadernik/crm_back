<?php

declare(strict_types=1);

namespace App\Repositories\Comment;

use App\Models\Comment\CustomComments;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

final class CommentRepository extends AbstractRepository implements CommentRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(CustomComments::query());
    }

    public function find(int $id): ?Model
    {
        return CustomComments::query()->find($id);
    }
}