<?php

declare(strict_types=1);

namespace App\Models\Dictionary;

enum DictionaryTypeEnum: int
{
    case PRODUCT_TITLE = 1;

    public static function captions(): array
    {
        return [
            'product_title' => self::PRODUCT_TITLE->value,
        ];
    }

    public static function fromCaption(string $caption): self
    {
        return match($caption) {
            'product_title' => self::PRODUCT_TITLE,
        };
    }
}