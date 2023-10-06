<?php

declare(strict_types=1);

namespace App\Services\Order;

use Illuminate\Support\Collection;

interface OrderExtendAllWithTotalCommentsServiceInterface
{
    /**
     * @param Collection $orders
     *
     * @return Collection
     */
    public function extendAllWithTotalComments(Collection $orders): Collection;
}