<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Board\Group;

use App\DTOs\Board\Group\CreateGroupDTO;
use App\DTOs\Board\Group\UpdateGroupDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Board\Group\CreateGroupRequest;
use App\Http\Requests\Board\Group\ListGroupRequest;
use App\Http\Requests\Board\Group\UpdateGroupRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Board\Group\GroupManagerInterface;
use App\Repositories\Board\Board\BoardRepositoryInterface;
use App\Repositories\Board\Group\GroupRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GroupController extends AbstractApiController
{
    public function index(
        int $boardId,
        ListGroupRequest $request,
        BoardRepositoryInterface $boardRepository,
        GroupRepositoryInterface $groupRepository
    ): JsonResponse
    {
        if (!$this->isAllowed('boards.group.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$board = $boardRepository->find($boardId)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        if (!$board->canUser($this->userId())) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $requestData = $request->validated();

        $requestData['filter']['board_id'] = $boardId;

        $sort = [
            'sort' => $requestData['sort'] ?? null,
            'order' => $requestData['order'] ?? null,
        ];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $items = $groupRepository->findAllBy(criteria: $requestData, sort: $sort, limit: $limit, offset: $offset);
        $total = $groupRepository->count($requestData);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }

    public function show(
        int $boardId,
        int $id,
        BoardRepositoryInterface $boardRepository,
        GroupRepositoryInterface $repository
    ): JsonResponse
    {
        if (!$this->isAllowed('boards.group.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$board = $boardRepository->find($boardId)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        if (!$board->canUser($this->userId())) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$group = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($group->toArray());
    }

    public function store(
        int $boardId,
        BoardRepositoryInterface $boardRepository,
        CreateGroupRequest $request,
        GroupManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('boards.group.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$board = $boardRepository->find($boardId)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        if (!$board->canUser($this->userId())) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $groupDTO = new CreateGroupDTO($request->validated());

        $group = $manager->create($groupDTO);

        // TODO: Dispatch event to socket here

        return ApiResponse::responseSuccess($group->toArray());
    }

    public function update(
        int $boardId,
        int $id,
        BoardRepositoryInterface $boardRepository,
        UpdateGroupRequest $request,
        GroupManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('boards.group.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$board = $boardRepository->find($boardId)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        if (!$board->canUser($this->userId())) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $groupDTO = new UpdateGroupDTO($request->validated());

        if (!$group = $manager->update($id, $groupDTO)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        // TODO: Dispatch event to socket here

        return ApiResponse::responseSuccess($group->toArray());
    }

    public function destroy(
        int $boardId,
        int $id,
        BoardRepositoryInterface $boardRepository,
        GroupManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('boards.group.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$board = $boardRepository->find($boardId)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        if (!$board->canUser($this->userId())) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$group = $manager->delete($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        // TODO: Dispatch event to socket here

        return ApiResponse::responseSuccess($group->toArray());
    }
}