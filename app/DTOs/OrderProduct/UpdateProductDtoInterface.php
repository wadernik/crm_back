<?php

declare(strict_types=1);

namespace App\DTOs\OrderProduct;

use App\DTOs\Sub\ImmutableNameInterface;
use Illuminate\Contracts\Support\Arrayable;

interface UpdateProductDtoInterface extends ImmutableNameInterface, Arrayable
{
}
