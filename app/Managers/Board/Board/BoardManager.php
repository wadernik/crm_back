<?php

declare(strict_types=1);

namespace App\Managers\Board\Board;

use App\DTOs\Board\Board\CreateBoardDTOInterface;
use App\DTOs\Board\Board\UpdateBoardDTOInterface;
use App\Models\Board\Board;

final class BoardManager implements BoardManagerInterface
{
    public function create(CreateBoardDTOInterface $boardDTO): Board
    {
        /** @var Board $board */
        $board = Board::query()->create($boardDTO->toArray());

        return $board;
    }

    public function update(int $id, UpdateBoardDTOInterface $boardDTO): ?Board
    {
        /** @var Board $board */
        if (!$board = Board::query()->find($id)) {
            return null;
        }

        $board->update($boardDTO->toArray());

        return $board;
    }

    public function delete(int $id): ?Board
    {
        /** @var Board $board */
        if (!$board = Board::query()->find($id)) {
            return null;
        }

        $board->delete();

        return $board;
    }
}