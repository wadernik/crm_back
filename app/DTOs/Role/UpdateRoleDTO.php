<?php

declare(strict_types=1);

namespace App\DTOs\Role;

final class UpdateRoleDTO implements UpdateRoleDTOInterface
{
    /**
     * @param array{
     *     name: string|null,
     *     label: string|null,
     *     permissions: array
     * } $attributes
     */
    public function __construct(private readonly array $attributes)
    {
    }

    public function name(): ?string
    {
        return $this->attributes['name'] ?? null;
    }

    public function label(): ?string
    {
        return $this->attributes['label'] ?? null;
    }

    public function permissions(): array
    {
        return $this->attributes['permissions'] ?? [];
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}