<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Board\Board;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Models\Board\Board;
use App\Models\Board\Group;
use Illuminate\Http\JsonResponse;

final class BoardsController extends AbstractApiController
{
    public function __invoke(): JsonResponse
    {
        $board = Board::query()->find(1);
        $group = Group::query()->find(1);

        // dd($board->toArray(), $board->users->toArray(), $board->groups->toArray(), $group->toArray());
        dd($group->board->toArray());
        return ApiResponse::responseSuccess();
    }
}