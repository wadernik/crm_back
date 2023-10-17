<?php

declare(strict_types=1);

namespace App\Managers\Board\Board;

use App\DTOs\Board\Board\CreateBoardDTOInterface;
use App\Models\Board\Board;

interface BoardCreatorInterface
{
    public function create(CreateBoardDTOInterface $boardDTO): Board;
}