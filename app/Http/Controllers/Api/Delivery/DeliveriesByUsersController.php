<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Delivery;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Delivery\ListDeliveryRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Delivery\OrderDeliveryRepository;

#[Permission('orders.view')]
final class DeliveriesByUsersController extends AbstractApiController
{
    public function __invoke(ListDeliveryRequest $request, OrderDeliveryRepository $repository)
    {
        $validated = $request->validated();

        $items = $repository->deliveriesGroupedByUser($validated);

        return ApiResponse::responseSuccess(
            $items,
        );
    }
}