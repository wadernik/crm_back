<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Activity;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Activities\ListActivitiesRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Activity\ActivityServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ActivityController extends AbstractApiController
{
    public function index(ListActivitiesRequest $request, ActivityServiceInterface $service): JsonResponse
    {
        if (!$this->isAllowed('activities.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $criteria = $request->validated();

        [$items, $total] = $service->activities(requestParams:  $criteria);

        return ApiResponse::responseSuccess(data: $items, total: $total);
    }
}