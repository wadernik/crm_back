<?php

declare(strict_types=1);

namespace App\Repositories\Setting;

use App\Models\Setting\Setting;

interface FindOneByIdInterface
{
    public function find(int $id): ?Setting;
}