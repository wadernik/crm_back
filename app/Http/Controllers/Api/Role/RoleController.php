<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Role;

use App\DTOs\Role\CreateRoleDTO;
use App\DTOs\Role\UpdateRoleDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Roles\CreateRoleRequest;
use App\Http\Requests\Roles\ListRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Role\RoleManagerInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class RoleController extends AbstractApiController
{
    public function index(ListRoleRequest $request, RoleRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('roles.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $criteria = $request->validated();

        $items = $repository->findAllBy($criteria);
        $total = $repository->count($criteria);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }

    public function show(int $id, RoleRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('roles.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$role = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($role->toArray());
    }

    public function store(CreateRoleRequest $request, RoleManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('roles.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $roleDTO = new CreateRoleDTO($request->validated());

        if (!$role = $manager->create($roleDTO)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($role->toArray());
    }

    public function update(int $id, UpdateRoleRequest $request, RoleManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('roles.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $roleDTO = new UpdateRoleDTO($request->validated());

        if (!$role = $manager->update($id, $roleDTO)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($role->toArray());
    }

    public function destroy(int $id, RoleManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('roles.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$role = $manager->delete($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($role->toArray());
    }
}