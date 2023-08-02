<?php

declare(strict_types=1);

namespace App\Models\Order\OrderWithComment;

use App\Models\Order\Order;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

interface OrderWithCommentsInterface extends Arrayable
{
    public function order(): Order;

    public function comments(): Collection;
}