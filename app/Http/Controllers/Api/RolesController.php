<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Roles\CreateRoleRequest;
use App\Http\Requests\Roles\UpdateRolesRequest;
use App\Services\RolesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RolesController extends BaseApiController
{
    public function __construct(
        private RolesService $rolesService
    ) {}

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        if (!$this->isAllowed('roles.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $roles = $this->rolesService->getRoles(with: ['permissions:id,label']);

            return $this->responseSuccess(data: $roles, headers: ['x-total-count' => count($roles)]);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        if (!$this->isAllowed('roles.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $role = $this->rolesService->getRole($id, ['permissions:id,label']);

            return $this->responseSuccess(data: $role);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param CreateRoleRequest $request
     * @return JsonResponse
     */
    public function store(CreateRoleRequest $request): JsonResponse
    {
        if (!$this->isAllowed('roles.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $roleId = $this->rolesService->createROle($request->validated());

            return $this->responseSuccess(data: ['id' => $roleId], code: Response::HTTP_CREATED);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param UpdateRolesRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateRolesRequest $request, $id): JsonResponse
    {
        if (!$this->isAllowed('roles.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();
            $this->rolesService->setPermissions($id, $validated['permissions']);

            return $this->responseSuccess();
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * This method is unused => not allowed
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {
        return $this->responseError(
            code: Response::HTTP_METHOD_NOT_ALLOWED,
            message: Response::$statusTexts[Response::HTTP_METHOD_NOT_ALLOWED]
        );
    }

    /**
     * This method is unused => not allowed
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        return $this->responseError(
            code: Response::HTTP_METHOD_NOT_ALLOWED,
            message: Response::$statusTexts[Response::HTTP_METHOD_NOT_ALLOWED]
        );
    }

    /**
     * This method is unused => not allowed
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        return $this->responseError(
            code: Response::HTTP_METHOD_NOT_ALLOWED,
            message: Response::$statusTexts[Response::HTTP_METHOD_NOT_ALLOWED]
        );
    }
}
