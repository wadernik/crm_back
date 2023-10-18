<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\Comment;

use App\DTOs\Comment\UpdateCommentDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Comments\EditCommentRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Comment\CommentManagerInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class EditOrderCommentController extends AbstractApiController
{
    public function __invoke(
        int $id,
        int $commentId,
        EditCommentRequest $request,
        OrderRepositoryInterface $orderRepository,
        CommentManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('comments.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$orderRepository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $commentDTO = new UpdateCommentDTO($request->validated()['comment']);

        if (!$comment = $manager->update($commentId, $commentDTO)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($comment->toArray());
    }
}