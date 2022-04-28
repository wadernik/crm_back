<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Dictionaries\PermissionsDictionaryRequest;
use App\Services\Permissions\PermissionsCollectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PermissionsController extends BaseApiController
{
    /**
     * Dictionary
     * @param PermissionsDictionaryRequest $request
     * @param PermissionsCollectionService $permissionsCollectionService
     * @return JsonResponse
     */
    public function all(
        PermissionsDictionaryRequest $request,
        PermissionsCollectionService $permissionsCollectionService
    ): JsonResponse {
        try {
            $validated = array_merge($request->validated(), ['sort' => 'id', 'order' => 'asc']);
            $permissions = $permissionsCollectionService->getInstances(requestParams: $validated);

            return $this->responseSuccess(data: $permissions, headers: ['x-total-count' => count($permissions)]);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
