<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;


use App\Http\Requests\Activities\ListActivitiesRequest;
use App\Models\ActivityExtended;
use App\Services\Activity\ActivitiesService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ActivitiesController extends AbstractBaseApiController
{
    public function listSubjects(): JsonResponse
    {
        $result = ActivityExtended::getSubjectsList();
        $total = count($result);

        return $this->responseSuccess(data: $result, headers: ['x-total-count' => $total]);
    }

    public function index(
        ListActivitiesRequest $request,
        ActivitiesService $activitiesService
    ): JsonResponse {
        if (!$this->isAllowed('activities.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();
        [$result, $total] = $activitiesService->getActivities(requestParams: $validated);

        return $this->responseSuccess(data: $result, headers: ['x-total-count' => $total]);
    }
}