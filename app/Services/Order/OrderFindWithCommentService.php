<?php

declare(strict_types=1);


namespace App\Services\Order;

use App\Models\Order\Order;
use App\Models\Order\OrderWithComment\OrderWithComments;
use App\Models\Order\OrderWithComment\OrderWithCommentsInterface;
use App\Models\Order\OrderWithTotalComments\OrderWithTotalComments;
use App\Models\Order\OrderWithTotalComments\OrderWithTotalCommentsInterface;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;

final class OrderFindWithCommentService implements OrderFindWithCommentServiceInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CommentRepositoryInterface $commentRepository,
    )
    {
    }

    public function findWithTotalComments(int $id): ?OrderWithTotalCommentsInterface
    {
        if (!$order = $this->orderRepository->find($id)) {
            return null;
        }

        $commentsTotal = $this->commentRepository->count(
            ['filter' => ['commentable_type' => Order::class, 'commentable_id' => $id]]
        );

        return new OrderWithTotalComments($order, $commentsTotal);
    }

    public function findWithComments(int $id, array $requestParams = []): ?OrderWithCommentsInterface
    {
        if (!$order = $this->orderRepository->find($id)) {
            return null;
        }

        $sort = ['sort' => $requestData['sort'] ?? null, 'order' => $requestData['order'] ?? null];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['offset'] ?? null;

        $comments = $this->commentRepository->findAllBy(
            criteria: ['filter' => ['commentable_type' => Order::class, 'commentable_id' => $id]],
            sort: $sort,
            limit: $limit,
            offset: $offset
        );

        return new OrderWithComments($order, $comments);
    }
}