<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Board\Board;

use App\DTOs\Board\Board\CreateBoardDTO;
use App\DTOs\Board\Board\UpdateBoardDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Board\Board\CreateBoardRequest;
use App\Http\Requests\Board\Board\ListBoardsRequest;
use App\Http\Requests\Board\Board\UpdateBoardRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Board\Board\BoardManagerInterface;
use App\Models\Board\Board;
use App\Repositories\Board\Board\BoardRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BoardController extends AbstractApiController
{
    public function index(ListBoardsRequest $request, BoardRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('boards.board.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

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

    public function show(int $id, BoardRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('boards.board.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$board = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($board->toArray());
    }

    public function store(CreateBoardRequest $request, BoardManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('boards.board.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $boardDTO = new CreateBoardDTO($request->validated());

        $board = $manager->create($boardDTO);

        return ApiResponse::responseSuccess($board->toArray());
    }

    public function update(
        int $id,
        UpdateBoardRequest $request,
        BoardManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('boards.board.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $boardDTO = new UpdateBoardDTO($request->validated());

        if (!$board = $manager->update($id, $boardDTO)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($board->toArray());
    }

    public function destroy(int $id, BoardManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('boards.board.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$board = $manager->delete($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($board->toArray());
    }
}