<?php

declare(strict_types=1);

namespace App\Models\Order\OrderWithTotalComments;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

interface OrderWithTotalCommentsInterface extends Arrayable
{
    public function order(): Model;

    public function totalComments(): int;
}