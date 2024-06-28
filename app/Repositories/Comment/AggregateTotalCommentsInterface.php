<?php

declare(strict_types=1);

namespace App\Repositories\Comment;

use Illuminate\Database\Eloquent\Collection;

interface AggregateTotalCommentsInterface
{
    /**
     * @param string $commentableType
     * @param array  $orderIds
     *
     * @return Collection<array{commentable_id: int, amount: int}>
     */
    public function aggregateByCommentableIds(string $commentableType, array $orderIds): Collection;
}