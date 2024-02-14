<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Models\Order\Order;
use App\Models\Order\OrderWithTotalComments\OrderWithTotalComments;
use App\Models\Order\OrderWithTotalComments\OrderWithTotalCommentsInterface;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Support\Collection;

final class OrderExtendAllWithTotalCommentsService implements OrderExtendAllWithTotalCommentsServiceInterface
{
    public function __construct(private readonly CommentRepositoryInterface $commentRepository)
    {
    }

    public function extendAllWithTotalComments(Collection $orders): Collection
    {
        return $orders->map(function (Order $order): OrderWithTotalCommentsInterface {
            $commentsTotal = $this->commentRepository->count(
                ['filter' => ['commentable_type' => Order::class, 'commentable_id' => $order->id]]
            );

            return new OrderWithTotalComments($order, $commentsTotal);
        });
    }
}