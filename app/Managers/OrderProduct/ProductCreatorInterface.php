<?php

declare(strict_types=1);

namespace App\Managers\OrderProduct;

use App\DTOs\OrderProduct\CreateProductDtoInterface;
use App\Models\Dictionary\Dictionary;

interface ProductCreatorInterface
{
    public function create(CreateProductDtoInterface $productDto): Dictionary;
}
