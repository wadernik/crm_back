<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Comments\CreateCommentRequest;
use App\Http\Requests\Comments\EditCommentRequest;
use App\Models\CustomComments;
use App\Models\Order;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * TODO: Refactor
 */
final class OrderCommentsController extends AbstractBaseApiController
{
    /**
     * @param int $orderId
     * @return JsonResponse
     */
    public function getComments(int $orderId): JsonResponse
    {
        if (!$this->isAllowed('comments.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        $order = Order::query()->find($orderId);

        if (!$order) {
            return $this->responseError(code: Response::HTTP_NOT_FOUND);
        }

        return $this->responseSuccess($order->comments->toArray());
    }

    /**
     * @param int $orderId
     * @param CreateCommentRequest $request
     * @return JsonResponse
     */
    public function postComment(
        int $orderId,
        CreateCommentRequest $request
    ): JsonResponse {
        if (!$this->isAllowed('comments.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        $comment = $request->validated()['comment'];

        $order = Order::query()->find($orderId);

        if (!$order) {
            return $this->responseError(code: Response::HTTP_NOT_FOUND);
        }

        $comment = $order->comment($comment);

        return $this->responseSuccess($comment->toArray(), code: Response::HTTP_CREATED);
    }

    /**
     * @param int $orderId
     * @param int $commentId
     * @param EditCommentRequest $request
     * @return JsonResponse
     */
    public function editComment(int $orderId, int $commentId, EditCommentRequest $request): JsonResponse
    {
        if (!$this->isAllowed('comments.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        $order = Order::query()->find($orderId);

        if (!$order) {
            return $this->responseError(code: Response::HTTP_NOT_FOUND);
        }

        $comment = CustomComments::query()->find($commentId);

        if (!$comment) {
            return $this->responseError(code: Response::HTTP_NOT_FOUND);
        }

        if ($comment->user_id !== auth('sanctum')->id()) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        $commentText = $request->validated()['comment'];

        $comment->update(['comment' => $commentText]);

        return $this->responseSuccess();
    }

    /**
     * @param int $orderId
     * @param int $commentId
     * @return JsonResponse
     */
    public function deleteComment(int $orderId, int $commentId): JsonResponse
    {
        if (!$this->isAllowed('comments.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        $order = Order::query()->find($orderId);

        if (!$order) {
            return $this->responseError(code: Response::HTTP_NOT_FOUND);
        }

        $comment = CustomComments::query()->find($commentId);

        if (!$comment) {
            return $this->responseError(code: Response::HTTP_NOT_FOUND);
        }

        if (
            $comment->user_id === auth('sanctum')->id()
            || auth('sanctum')->user()->role->id === Role::ROLE_ADMIN
        ) {
            $comment->delete();

            return $this->responseSuccess();
        }

        return $this->responseError(code: Response::HTTP_FORBIDDEN);
    }
}