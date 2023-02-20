<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Orders\CreateOrderRequest;
use App\Http\Requests\Orders\ListOrdersActivityRequest;
use App\Http\Requests\Orders\ListOrdersRequest;
use App\Http\Requests\Orders\PrintOrdersRequest;
use App\Http\Requests\Orders\UpdateOrderRequest;
use App\Http\Requests\Orders\UpdateOrderStatusRequest;
use App\Services\ManufacturersDateLimits\ManufacturerDateLimitsCollectionService;
use App\Services\Orders\OrderActivitiesService;
use App\Services\Orders\OrderInstanceService;
use App\Services\Orders\OrdersCollectionService;
use App\Services\Orders\OrdersExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends AbstractBaseApiController
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
            $validated = $request->validated();

            $attributes = [
                'orders.*',
                'order_details.id AS order_detail_id',
                'order_details.name',
                'order_details.amount',
                'order_details.label',
                'order_details.decoration',
                'order_details.comment',
            ];

            $ordersCollectionService->setJoins(
                [
                    'table' => 'users',
                    'first' => 'users.id',
                    'operator' => '=',
                    'second' => 'orders.user_id',
                ]
            );

            $orders = $ordersCollectionService->getInstances(
                attributes: $attributes,
                requestParams: $validated,
                with: ['files:id,filename']
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
            $attributes = [
                'orders.*',
                'order_details.id AS order_detail_id',
                'order_details.name',
                'order_details.amount',
                'order_details.label',
                'order_details.decoration',
                'order_details.comment',
            ];

            $order = $orderInstanceService->getInstance(id: $id, attributes: $attributes, with: ['files:id,filename']);
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
     * @param ManufacturerDateLimitsCollectionService $manufacturerDateLimitsCollectionService
     * @return JsonResponse
     */
    public function store(
        CreateOrderRequest $request,
        OrderInstanceService $orderInstanceService,
        ManufacturerDateLimitsCollectionService $manufacturerDateLimitsCollectionService
    ): JsonResponse {
        if (!$this->isAllowed('orders.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();

            if (!$manufacturerDateLimitsCollectionService->canAcceptOrderForDate(
                manufacturerId: $validated['manufacturer_id'],
                date: $validated['order_date'])
            ) {
                return $this->responseError(
                    code: Response::HTTP_UNPROCESSABLE_ENTITY,
                    message: __('order.limited_date')
                );
            }

            if (!isset($validated['user_id'])) {
                $validated['user_id'] = auth('sanctum')->id();
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
     * @param ManufacturerDateLimitsCollectionService $manufacturerDateLimitsCollectionService
     * @return JsonResponse
     */
    public function update(
        int $id,
        UpdateOrderRequest $request,
        OrderInstanceService $orderInstanceService,
        ManufacturerDateLimitsCollectionService $manufacturerDateLimitsCollectionService
    ): JsonResponse {
        if (!$this->isAllowed('orders.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();

            $manufacturerId = $validated['manufacturer_id'] ?? null;
            $orderDate = '';

            if (!isset($validated['order_date'], $validated['manufacturer_id'])) {
                $order = $orderInstanceService->getInstance($id, ['manufacturer_id']);

                $manufacturerId = $manufacturerId ?: $order['manufacturer_id'];
                $orderDate = $validated['order_date'] ?? $order['order_date'];
            }

            if (!$manufacturerDateLimitsCollectionService->canAcceptOrderForDate($manufacturerId, $orderDate)) {
                return $this->responseError(
                    code: Response::HTTP_UNPROCESSABLE_ENTITY,
                    message: __('order.limited_date')
                );
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

    /**
     * @param UpdateOrderStatusRequest $request
     * @param OrderInstanceService $orderInstanceService
     * @return JsonResponse
     */
    public function updateStatus(
        UpdateOrderStatusRequest $request,
        OrderInstanceService $orderInstanceService
    ): JsonResponse {
        if (!$this->isAllowed('orders.process')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();

            $id = $validated['id'];
            unset($validated['id']);

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

    /**
     * @param int $id
     * @param OrderInstanceService $orderInstanceService
     * @return JsonResponse
     */
    public function destroy(int $id, OrderInstanceService $orderInstanceService): JsonResponse
    {
        if (!$this->isAllowed('orders.delete')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$orderInstanceService->deleteInstance($id)) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * TODO: put to standalone controller class
     * @param PrintOrdersRequest $request
     * @param OrdersCollectionService $ordersCollectionService
     * @param OrdersExportService $orderExportService
     * @return BinaryFileResponse|JsonResponse
     */
    public function export(
        PrintOrdersRequest $request,
        OrdersCollectionService $ordersCollectionService,
        OrdersExportService $orderExportService
    ): BinaryFileResponse|JsonResponse {
        if (!$this->isAllowed('orders.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $orderIds = $request->validated();
            $requestParams = [
                'filter' => ['ids' => $orderIds['ids']],
            ];

            $orders = $ordersCollectionService->getInstances(
                requestParams: $requestParams,
                with: ['files:id,filename', 'manufacturer', 'seller', 'source']
            );

            if (!$orders) {
                return $this->responseError(code: Response::HTTP_NOT_FOUND);
            }

            [$path, $fileName] = $orderExportService->exportPdf($orders);

            return $this->responseBinary($path, $fileName);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @TODO: отрефакторить это дерьмо
     * @param int $id
     * @param ListOrdersActivityRequest $request
     * @param OrderActivitiesService $service
     * @return JsonResponse
     */
    public function activities(
        int $id,
        ListOrdersActivityRequest $request,
        OrderActivitiesService $service
    ): JsonResponse {
        if (!$this->isAllowed('orders.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();

        [$results, $total] = $service->getActivities(
            subjectId: $id,
            requestParams: $validated
        );

        return $this->responseSuccess(
            data: $results,
            headers: ['x-total-count' => $total]
        );
    }
}
