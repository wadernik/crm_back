<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Dictionaries\RolesDictionaryRequest;
use App\Http\Requests\Roles\CreateRoleRequest;
use App\Http\Requests\Roles\ListRolesRequest;
use App\Http\Requests\Roles\UpdateRolesRequest;
use App\Services\Roles\RoleInstanceService;
use App\Services\Roles\RolesCollectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RolesController extends BaseApiController
{
    /**
     * Dictionary
     * @param RolesDictionaryRequest $request
     * @param RolesCollectionService $rolesCollectionService
     * @return JsonResponse
     */
    public function all(RolesDictionaryRequest $request, RolesCollectionService $rolesCollectionService): JsonResponse
    {
        try {
            $validated = array_merge($request->validated(), ['sort' => 'id', 'order' => 'asc']);
            $roles = $rolesCollectionService->getInstances(requestParams: $validated, with: ['permissions']);
            $total = $rolesCollectionService->countInstances($validated);

            return $this->responseSuccess(data: $roles, headers: ['x-total-count' => $total]);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param ListRolesRequest $request
     * @param RolesCollectionService $rolesCollectionService
     * @return JsonResponse
     */
    public function index(ListRolesRequest $request, RolesCollectionService $rolesCollectionService): JsonResponse
    {
        if (!$this->isAllowed('roles.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();
            $roles = $rolesCollectionService->getInstances(requestParams: $validated, with: ['permissions']);
            $total = $rolesCollectionService->countInstances($validated);

            return $this->responseSuccess(data: $roles, headers: ['x-total-count' => $total]);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param RoleInstanceService $roleInstanceService
     * @return JsonResponse
     */
    public function show(int $id, RoleInstanceService $roleInstanceService): JsonResponse
    {
        if (!$this->isAllowed('roles.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$role = $roleInstanceService->getInstance($id, with: ['permissions'])) {
                return $this->responseError(code: Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess(data: $role);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param CreateRoleRequest $request
     * @param RoleInstanceService $roleInstanceService
     * @return JsonResponse
     */
    public function store(CreateRoleRequest $request, RoleInstanceService $roleInstanceService): JsonResponse
    {
        if (!$this->isAllowed('roles.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $role = $roleInstanceService->createInstance($request->validated());

            return $this->responseSuccess(data: ['id' => $role['id']], code: Response::HTTP_CREATED);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param UpdateRolesRequest $request
     * @param RoleInstanceService $roleInstanceService
     * @return JsonResponse
     */
    public function update(int $id, UpdateRolesRequest $request, RoleInstanceService $roleInstanceService): JsonResponse
    {
        if (!$this->isAllowed('roles.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$roleInstanceService->editInstance($id, $request->validated())) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess();
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param RoleInstanceService $roleInstanceService
     * @return JsonResponse
     */
    public function destroy(int $id, RoleInstanceService $roleInstanceService): JsonResponse
    {
        if (!$this->isAllowed('roles.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$roleInstanceService->deleteInstance($id)) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess();
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
