<?php

declare(strict_types=1);

namespace App\DTOs\OrderProduct;

final class UpdateProductDto implements UpdateProductDtoInterface
{
    /**
     * @param array{
     *     name: string|null,
     * } $attributes
     */
    public function __construct(private readonly array $attributes)
    {
    }

    public function name(): string
    {
        return $this->attributes['name'];
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}
