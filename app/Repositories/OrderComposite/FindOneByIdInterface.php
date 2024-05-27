<?php

declare(strict_types=1);

namespace App\Repositories\OrderComposite;

use App\DTOs\Order\Composite\OrderComposite;

interface FindOneByIdInterface
{
    public function find(int $id): ?OrderComposite;
}