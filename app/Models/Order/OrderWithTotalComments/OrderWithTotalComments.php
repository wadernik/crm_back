<?php

declare(strict_types=1);

namespace App\Models\Order\OrderWithTotalComments;

use Illuminate\Database\Eloquent\Model;

final class OrderWithTotalComments implements OrderWithTotalCommentsInterface
{
    public function __construct(private Model $order, private int $total)
    {
    }

    public function order(): Model
    {
        return $this->order;
    }

    public function totalComments(): int
    {
        return $this->total;
    }

    public function toArray(): array
    {
        $order = $this->order->toArray();

        return array_merge($order, ['total_comments' => $this->total]);
    }
}