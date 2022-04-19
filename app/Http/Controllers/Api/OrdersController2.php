<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Orders\CreateOrderRequest;
use App\Services\Orders\OrderInstanceService;
use App\Services\Orders\OrdersCollectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrdersController2 extends BaseApiController
{
    /**
     * Dictionary
     * @param OrdersCollectionService $ordersCollectionService
     * @return JsonResponse
     */
    public function statuses(OrdersCollectionService $ordersCollectionService): JsonResponse
    {
        try {
            $statuses = $ordersCollectionService->getStatuses();

            return $this->responseSuccess(data: $statuses, headers: ['x-total-count' => count($statuses)]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function store(CreateOrderRequest $request, OrderInstanceService $orderInstanceService): JsonResponse
    {

    }
}
