<?php

declare(strict_types=1);

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Permission
{
    public function __construct(private readonly string $permission)
    {
    }
}