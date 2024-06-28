<?php

declare(strict_types=1);

namespace App\Services\Order\Product;

use App\Http\Requests\Orders\OrderProductRequest;

interface DeleteOrderProductByRequestServiceInterface
{
    public function delete(OrderProductRequest $request): void;
}