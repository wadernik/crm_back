<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\OrderDraft\V2;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Managers\OrderComposite\OrderCompositeManagerInterface;
use App\Repositories\Order\OrderDraftRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Permission('orders.edit')]
final class DeleteOrderDraftController extends AbstractApiController
{
    public function __invoke(
        int $id,
        OrderDraftRepositoryInterface $orderRepository,
        OrderCompositeManagerInterface $manager
    ): JsonResponse
    {
        if (!$order = $orderRepository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $manager->delete($order);

        return ApiResponse::responseSuccess();
    }
}