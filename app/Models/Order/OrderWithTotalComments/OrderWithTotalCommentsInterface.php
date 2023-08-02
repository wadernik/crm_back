<?php

declare(strict_types=1);

namespace App\Models\Order\OrderWithTotalComments;

use App\Models\Order\Order;
use Illuminate\Contracts\Support\Arrayable;

interface OrderWithTotalCommentsInterface extends Arrayable
{
    public function order(): Order;

    public function totalComments(): int;
}