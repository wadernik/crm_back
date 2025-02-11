<?php

declare(strict_types=1);

namespace App\Repositories\User;

interface ExistsByIdInterface
{
    public function existsById(int $id): bool;
}