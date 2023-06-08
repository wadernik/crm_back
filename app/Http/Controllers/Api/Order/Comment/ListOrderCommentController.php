<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\Comment;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ListOrderCommentController extends AbstractApiController
{
    public function comments(int $id, OrderRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('comments.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$order = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($order->comments->toArray());
    }
}