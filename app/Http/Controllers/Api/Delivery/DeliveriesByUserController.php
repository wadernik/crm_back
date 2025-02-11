<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Delivery;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Delivery\ListDeliveryByUserRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Delivery\OrderDeliveryRepository;
use App\Repositories\User\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

#[Permission('orders.view')]
final class DeliveriesByUserController extends AbstractApiController
{
    public function __invoke(
        int $userId,
        ListDeliveryByUserRequest $request,
        OrderDeliveryRepository $repository,
        UserRepositoryInterface $userRepository
    )
    {
        if (!$userRepository->existsById($userId)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validated();

        $validated['filter']['courier_id'] = $userId;

        $sort = [
            'sort' => $requestData['sort'] ?? null,
            'order' => $requestData['order'] ?? null,
        ];

        $limit = $requestData['limit'] ?? null;

        $offset = $requestData['page'] ?? null;

        $items = $repository->findAllBy(criteria: $validated, sort: $sort, limit: $limit, offset: $offset);

        $total = $repository->count($validated);

        return ApiResponse::responseSuccess($items->toArray(), $total);
    }
}