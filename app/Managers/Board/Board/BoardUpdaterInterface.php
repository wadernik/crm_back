<?php

declare(strict_types=1);

namespace App\Managers\Board\Board;

use App\DTOs\Board\Board\UpdateBoardDTOInterface;
use App\Models\Board\Board;

interface BoardUpdaterInterface
{
    public function update(int $id, UpdateBoardDTOInterface $boardDTO): ?Board;
}