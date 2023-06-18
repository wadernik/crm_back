<?php

declare(strict_types=1);


namespace App\Services\Order;

use App\Models\Order\BaseOrder;
use App\Models\Order\OrderWithComment\OrderWithComments;
use App\Models\Order\OrderWithComment\OrderWithCommentsInterface;
use App\Models\Order\OrderWithTotalComments\OrderWithTotalComments;
use App\Models\Order\OrderWithTotalComments\OrderWithTotalCommentsInterface;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;

final class OrderFindWithCommentService implements OrderFindWithCommentServiceInterface
{
    private OrderRepositoryInterface $orderRepository;
    private CommentRepositoryInterface $commentRepository;

    public function __construct()
    {
        $this->orderRepository = app(OrderRepositoryInterface::class);
        $this->commentRepository = app(CommentRepositoryInterface::class);
    }

    public function findWithTotalComments(int $id): ?OrderWithTotalCommentsInterface
    {
        $this->orderRepository->applyWith(['files:id,filename']);

        if (!$order = $this->orderRepository->find($id)) {
            return null;
        }

        $commentsTotal = $this->commentRepository->count(
            ['filter' => ['commentable_type' => BaseOrder::class, 'commentable_id' => $id]]
        );

        return new OrderWithTotalComments($order, $commentsTotal);
    }

    public function findWithComments(int $id, array $requestParams = []): ?OrderWithCommentsInterface
    {
        $this->orderRepository->applyWith(['files:id,filename']);

        if (!$order = $this->orderRepository->find($id)) {
            return null;
        }

        $sort = ['sort' => $requestData['sort'] ?? null, 'order' => $requestData['order'] ?? null];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['offset'] ?? null;

        $comments = $this->commentRepository->findAllBy(
            criteria: ['filter' => ['commentable_type' => BaseOrder::class, 'commentable_id' => $id]],
            sort: $sort,
            limit: $limit,
            offset: $offset
        );

        return new OrderWithComments($order, $comments);
    }
}