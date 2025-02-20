<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order;

use App\Attributes\Permission;
use App\DTOs\Order\OrderCounterFilterDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\OrderCounterRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Order\Delivery\OrderCounterService;
use Illuminate\Http\JsonResponse;

#[Permission('orders.view')]
final class OrderCounterController extends AbstractApiController
{
    public function __invoke(OrderCounterRequest $request, OrderCounterService $counterService): JsonResponse
    {
        $validated = $request->validated();

        $responseDTO = $counterService->countByFilter(new OrderCounterFilterDTO($validated, $this->userId()));

        return ApiResponse::responseSuccess($responseDTO->getResponseAsArray());
    }
}