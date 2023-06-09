<?php

declare(strict_types=1);

namespace App\Models\Order\OrderWithComment;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface OrderWithCommentsInterface extends Arrayable
{
    public function order(): Model;

    public function comments(): Collection;
}