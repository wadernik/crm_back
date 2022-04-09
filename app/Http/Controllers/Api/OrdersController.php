<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Orders\CreateOrderRequest;
use App\Http\Requests\Orders\ListOrdersRequest;
use App\Services\ManufacturersService;
use App\Services\OrdersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends BaseApiController
{
    public function __construct(
        private OrdersService $ordersService
    ) {}

    public function statuses(): JsonResponse
    {
        try {
            $statuses = $this->ordersService->getStatuses();

            return $this->responseSuccess(data: $statuses, headers: ['x-total-count' => count($statuses)]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function index(ListOrdersRequest $request): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $attributes = ['*'];
            $orders = $this->ordersService->getOrders(
                attributes: $attributes,
                requestParams: $request->validated(),
                with: ['details']
            );

            return $this->responseSuccess(data: $orders, headers: ['x-total-count' => count($orders)]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function store(CreateOrderRequest $request, ManufacturersService $manufacturersService): JsonResponse
    {
        if (!$this->isAllowed('orders.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();

            $manufacturer = $manufacturersService->getManufacturer($validated['id'], ['id', 'limit']);

            if (!$manufacturer) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess(data: ['id' => 1], code: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
