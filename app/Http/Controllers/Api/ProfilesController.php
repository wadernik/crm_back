<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Users\UpdateUserRequest;
use App\Services\Users\UserInstanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProfilesController extends BaseApiController
{
    /**
     * @param UserInstanceService $userInstanceService
     * @return JsonResponse
     */
    public function show(UserInstanceService $userInstanceService): JsonResponse
    {
        try {
            $attributes = [
                'id',
                'first_name',
                'last_name',
                'email',
                'phone',
                'sex',
                'birth_date',
                'last_seen',
                'role_id',
            ];
            $with = ['role.permissions'];

            if (!$userId = auth()->id()) {
                return $this->responseError(code: Response::HTTP_NOT_FOUND);
            }

            if (!$user = $userInstanceService->getInstance($userId, $attributes, $with)) {
                return $this->responseError(code: Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess(data: $user);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param int $id
     * @param UpdateUserRequest $request
     * @param UserInstanceService $userInstanceService
     * @return JsonResponse
     */
    public function update(int $id, UpdateUserRequest $request, UserInstanceService $userInstanceService): JsonResponse
    {
        try {
            if (!$userId = auth()->id()) {
                return $this->responseError(code: Response::HTTP_NOT_FOUND);
            }

            if ($userId !== $id) {
                return $this->responseError(code: Response::HTTP_BAD_REQUEST);
            }

            if (!$user = $userInstanceService->editInstance($userId, $request->validated())) {
                return $this->responseError(code: Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess(data: $user->toArray());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
