<?php

declare(strict_types=1);

namespace App\DTOs\User;

final class UpdateUserDTO implements UpdateUserDTOInterface
{
    /**
     * @param array{
     *     first_name: string|null,
     *     last_name: string|null,
     *     phone: string|null,
     *     email: string|null,
     *     birth_date: string|null,
     *     sex: int|null,
     *     role_id: int|null,
     *     last_seen: string|null,
     * } $attributes
     */
    public function __construct(private readonly array $attributes)
    {
    }

    public function firstName(): ?string
    {
        return $this->attributes['first_name'] ?? null;
    }

    public function lastName(): ?string
    {
        return $this->attributes['last_name'] ?? null;
    }

    public function phone(): ?string
    {
        return $this->attributes['phone'] ?? null;
    }

    public function email(): ?string
    {
        return $this->attributes['email'] ?? null;
    }

    public function birthDate(): ?string
    {
        return $this->attributes['birth_date'] ?? null;
    }

    public function sex(): ?int
    {
        return $this->attributes['sex'] ?? null;
    }

    public function role(): ?int
    {
        return $this->attributes['role_id'];
    }

    public function lastSeen(): ?string
    {
        return $this->attributes['last_seen'] ?? null;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}