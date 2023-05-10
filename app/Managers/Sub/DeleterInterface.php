<?php

declare(strict_types=1);

namespace App\Managers\Sub;

use Illuminate\Database\Eloquent\Model;

interface DeleterInterface
{
    public function delete(int $id): ?Model;
}