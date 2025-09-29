<?php

declare(strict_types=1);

namespace App\Managers\OrderProduct;

use App\DTOs\OrderProduct\UpdateProductDtoInterface;
use App\Models\Dictionary\Dictionary;

interface ProductUpdaterInterface
{
    public function update(Dictionary $product, UpdateProductDtoInterface $productDto): Dictionary;
}
