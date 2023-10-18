<?php

declare(strict_types=1);

namespace App\Managers\Board\Board;

use App\Models\Board\Board;

interface BoardDeleterInterface
{
    public function delete(int $id): ?Board;
}