<?php

declare(strict_types=1);

namespace App\Repositories\Sub;

interface ApplyWithInterface
{
    public function applyWith(array $with): void;
}