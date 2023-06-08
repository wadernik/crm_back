<?php

declare(strict_types=1);

namespace App\Repositories\Sub;

interface ApplyWithTrashedInterface
{
    public function withTrashed(): void;
}