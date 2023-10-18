<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\Comment;

use App\DTOs\Comment\CreateCommentDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Comments\CreateCommentRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class PostOrderCommentController extends AbstractApiController
{
    public function __invoke(int $id, CreateCommentRequest $request, OrderRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('comments.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $commentDTO = new CreateCommentDTO($request->validated()['comment']);

        if (!$order = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $comment = $order->comment($commentDTO->comment());

        return ApiResponse::responseSuccess($comment->toArray());
    }
}