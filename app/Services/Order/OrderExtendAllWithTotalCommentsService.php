<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Models\Order\Order;
use App\Models\Order\OrderWithTotalComments\OrderWithTotalComments;
use App\Models\Order\OrderWithTotalComments\OrderWithTotalCommentsInterface;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Support\Collection;
use function app;

final class OrderExtendAllWithTotalCommentsService implements OrderExtendAllWithTotalCommentsServiceInterface
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct()
    {
        $this->commentRepository = app(CommentRepositoryInterface::class);
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