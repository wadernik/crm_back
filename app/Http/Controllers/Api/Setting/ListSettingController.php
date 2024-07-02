<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Setting;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\OrderSetting\ListSettingRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Setting\SettingRepositoryInterface;
use Illuminate\Http\JsonResponse;

#[Permission('settings.view')]
final class ListSettingController extends AbstractApiController
{
    public function __invoke(ListSettingRequest $request, SettingRepositoryInterface $repository): JsonResponse
    {
        $requestData = $request->validated();

        $sort = [
            'sort' => $requestData['sort'] ?? null,
            'order' => $requestData['order'] ?? null,
        ];

        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $items = $repository->findAllBy(criteria: $requestData, sort: $sort, limit: $limit, offset: $offset);
        $total = $repository->count($requestData);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }
}