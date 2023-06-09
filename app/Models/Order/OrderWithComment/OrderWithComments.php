<?php

declare(strict_types=1);

namespace App\Models\Order\OrderWithComment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final class OrderWithComments implements OrderWithCommentsInterface
{
    public function __construct(private Model $order, private Collection $comments)
    {
    }

    public function order(): Model
    {
        return $this->order;
    }

    public function comments(): Collection
    {
        return $this->comments;
    }

    public function toArray(): array
    {
        $order = $this->order->toArray();

        $comments = [];

        foreach ($this->comments as $comment) {
            $comments[] = $comment->toArray();
        }

        return array_merge($order, ['comments' => $comments]);
    }
}