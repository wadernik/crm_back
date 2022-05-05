<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Orders\CreateOrderRequest;
use App\Http\Requests\Orders\UpdateOrderRequest;
use App\Http\Requests\Orders\ListOrdersRequest;
use App\Services\Manufacturers\ManufacturerInstanceService;
use App\Services\Orders\OrderInstanceService;
use App\Services\Orders\OrdersCollectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends BaseApiController
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

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param ListOrdersRequest $request
     * @param OrdersCollectionService $ordersCollectionService
     * @return JsonResponse
     */
    public function index(ListOrdersRequest $request, OrdersCollectionService $ordersCollectionService): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $attributes = ['*'];
            $validated = $request->validated();
            $orders = $ordersCollectionService->getInstances(
                attributes: $attributes,
                requestParams: $validated,
                with: ['details', 'files:id,filename']
            );
            $total = $ordersCollectionService->countInstances($validated);

            return $this->responseSuccess(data: $orders, headers: ['x-total-count' => $total]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param int $id
     * @param OrderInstanceService $orderInstanceService
     * @return JsonResponse
     */
    public function show(int $id, OrderInstanceService $orderInstanceService): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $order = $orderInstanceService->getInstance(id: $id, with: ['details', 'files:id,filename']);
            if (!$order) {
                return $this->responseError(code: Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess(data: $order);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param CreateOrderRequest $request
     * @param OrderInstanceService $orderInstanceService
     * @param ManufacturerInstanceService $manufacturerInstanceService
     * @return JsonResponse
     */
    public function store(
        CreateOrderRequest $request,
        OrderInstanceService $orderInstanceService,
        ManufacturerInstanceService $manufacturerInstanceService
    ): JsonResponse {
        if (!$this->isAllowed('orders.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();
            $manufacturer = $manufacturerInstanceService->getInstance($validated['manufacturer_id'], ['id', 'limit']);

            if (!$manufacturer) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if (!$orderInstanceService->canCreateOrder($validated['order_date'], $manufacturer)) {
                return $this->responseError(
                    code: Response::HTTP_UNPROCESSABLE_ENTITY,
                    message: __('order.limit_reached')
                );
            }

            if (!isset($validated['user_id'])) {
                $validated['user_id'] = auth()->id();
            }

            $order = $orderInstanceService->createInstance($validated);

            if (!$order) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess(data: $order->toArray(), code: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param int $id
     * @param UpdateOrderRequest $request
     * @param OrderInstanceService $orderInstanceService
     * @param ManufacturerInstanceService $manufacturerInstanceService
     * @return JsonResponse
     */
    public function update(
        int $id,
        UpdateOrderRequest $request,
        OrderInstanceService $orderInstanceService,
        ManufacturerInstanceService $manufacturerInstanceService
    ): JsonResponse {
        if (!$this->isAllowed('orders.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();

            if (
                isset($validated['manufacturer_id'])
                && !$manufacturerInstanceService->getInstance($validated['manufacturer_id'])
            ) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if (!isset($validated['user_id'])) {
                $validated['user_id'] = auth()->id();
            }

            if (!$order = $orderInstanceService->editInstance($id, $validated)) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess(data: $order->toArray());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
