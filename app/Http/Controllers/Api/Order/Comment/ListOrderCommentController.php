<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\Comment;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Comments\ListCommentRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Order\Order;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ListOrderCommentController extends AbstractApiController
{
    public function comments(
        int $id,
        ListCommentRequest $request,
        OrderRepositoryInterface $orderRepository,
        CommentRepositoryInterface $commentRepository
    ): JsonResponse
    {
        if (!$this->isAllowed('comments.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$orderRepository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $requestData = $request->validated();

        $criteria = ['filter' => ['commentable_type' => Order::class, 'commentable_id' => $id]];

        $sort = ['sort' => $requestData['sort'] ?? 'created_at', 'order' => $requestData['order'] ?? 'desc'];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['offset'] ?? null;

        $comments = $commentRepository->findAllBy(
            criteria: $criteria,
            sort: $sort,
            limit: $limit,
            offset: $offset
        );

        return ApiResponse::responseSuccess(data: $comments->toArray(), total: $commentRepository->count($criteria));
    }
}