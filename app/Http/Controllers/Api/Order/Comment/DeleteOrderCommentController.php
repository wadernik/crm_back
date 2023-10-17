<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\Comment;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Models\Role\Role;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DeleteOrderCommentController extends AbstractApiController
{
    public function __invoke(
        int $orderId,
        int $commentId,
        OrderRepositoryInterface $orderRepository,
        CommentRepositoryInterface $commentRepository,
    ): JsonResponse
    {
        if (!$this->isAllowed('comments.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$orderRepository->find($orderId)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        if (!$comment = $commentRepository->find($commentId)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        if (
            $comment->user_id === $this->userId()
            || $this->user()->role->id === Role::ROLE_ADMIN
        ) {
            $comment->delete();

            return ApiResponse::responseSuccess($comment->toArray());
        }

        return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
    }
}