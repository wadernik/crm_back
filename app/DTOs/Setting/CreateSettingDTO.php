<?php

declare(strict_types=1);

namespace App\DTOs\Setting;

class CreateSettingDTO implements CreateSettingDTOInterface
{
    /**
     * @param array{
     *     type_id: int,
     *     value: string,
     * } $attributes
     */
    public function __construct(private readonly array $attributes)
    {
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}