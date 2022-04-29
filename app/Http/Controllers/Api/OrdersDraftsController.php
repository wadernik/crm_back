<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Orders\CreateOrderDraftRequest;
use App\Http\Requests\Orders\UpdateOrderDraftRequest;
use App\Services\Orders\OrderDraftInstanceService;
use App\Services\Orders\OrdersDraftsCollectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrdersDraftsController extends BaseApiController
{
    /**
     * @param OrdersDraftsCollectionService $ordersDraftsCollectionService
     * @return JsonResponse
     */
    public function index(OrdersDraftsCollectionService $ordersDraftsCollectionService): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $attributes = ['*'];
            $requestParams = ['filter' => ['user_id' => auth()->id()]];
            $with = ['details'];

            $orders = $ordersDraftsCollectionService->getInstances(
                attributes: $attributes,
                requestParams: $requestParams,
                with: $with
            );
            $total = $ordersDraftsCollectionService->countInstances($requestParams);

            return $this->responseSuccess(data: $orders, headers: ['x-total-count' => $total]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param OrderDraftInstanceService $orderDraftInstanceService
     * @return JsonResponse
     */
    public function show(int $id, OrderDraftInstanceService $orderDraftInstanceService): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $attributes = ['*'];
            $with = ['details', 'files:id,filename'];

            $orders = $orderDraftInstanceService->getInstance(
                $id,
                attributes: $attributes,
                with: $with
            );

            return $this->responseSuccess(data: $orders, headers: ['x-total-count' => count($orders)]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param CreateOrderDraftRequest $request
     * @param OrderDraftInstanceService $orderDraftInstanceService
     * @return JsonResponse
     */
    public function store(
        CreateOrderDraftRequest $request,
        OrderDraftInstanceService $orderDraftInstanceService
    ): JsonResponse {
        if (!$this->isAllowed('orders.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();

            // TODO think about this one
            $validated['user_id'] = auth()->id();

            if (!$order = $orderDraftInstanceService->createInstance($validated)) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess(data: ['id' => $order['id']], code: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param UpdateOrderDraftRequest $request
     * @param OrderDraftInstanceService $orderDraftInstanceService
     * @return JsonResponse
     */
    public function update(
        int $id,
        UpdateOrderDraftRequest $request,
        OrderDraftInstanceService $orderDraftInstanceService
    ): JsonResponse {
        if (!$this->isAllowed('orders.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();

            // TODO think about this one
            $validated['user_id'] = auth()->id();

            if (!$orderDraftInstanceService->editInstance($id, $validated)) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param OrderDraftInstanceService $orderDraftInstanceService
     * @return JsonResponse
     */
    public function destroy(int $id, OrderDraftInstanceService $orderDraftInstanceService): JsonResponse
    {
        if (!$this->isAllowed('orders.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$orderDraftInstanceService->deleteInstance($id)) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
