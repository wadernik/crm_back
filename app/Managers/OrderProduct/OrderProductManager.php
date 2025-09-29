<?php

declare(strict_types=1);

namespace App\Managers\OrderProduct;

use App\DTOs\OrderProduct\CreateProductDtoInterface;
use App\DTOs\OrderProduct\UpdateProductDtoInterface;
use App\Models\Dictionary\Dictionary;
use App\Models\Dictionary\DictionaryTypeEnum;

final class OrderProductManager implements ProductManagerInterface
{
    public function create(CreateProductDtoInterface $productDto): Dictionary
    {
        $attributes = [
            'type' => DictionaryTypeEnum::PRODUCT_TITLE->value,
            'value' => $productDto->name(),
        ];

        /** @var Dictionary $dictionary */
        $dictionary = Dictionary::query()->create($attributes);

        return $dictionary;
    }

    public function update(Dictionary $product, UpdateProductDtoInterface $productDto): Dictionary
    {
        if ($productDto->name()) {

            $product->update(['value' => $productDto->name()]);
        }

        return $product;
    }

    public function delete(Dictionary $product): Dictionary
    {
        $product->delete();

        return $product;
    }
}
