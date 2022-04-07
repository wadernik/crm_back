<?php

namespace App\Http\Controllers\Api;

use App\Services\PermissionsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PermissionsController extends BaseApiController
{
    public function all(PermissionsService $permissionsService): JsonResponse
    {
        try {
            $permissions = $permissionsService->getPermissions();

            return $this->responseSuccess(data: $permissions, headers: ['x-total-count' => count($permissions)]);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
