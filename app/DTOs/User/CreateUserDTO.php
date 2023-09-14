<?php

declare(strict_types=1);

namespace App\DTOs\User;

final class CreateUserDTO implements CreateUserDTOInterface
{
    /**
     * @param array{
     *     password: string,
     *     first_name: string,
     *     last_name: string|null,
     *     phone: string,
     *     email: string|null,
     *     birth_date: string|null,
     *     sex: int|null,
     *     role_id: int,
     * } $attributes
     */
    public function __construct(private readonly array $attributes)
    {
    }

    //public function username(): string
    //{
    //    return $this->attributes['username'];
    //}

    public function password(): string
    {
        return $this->attributes['password'];
    }

    public function firstName(): string
    {
        return $this->attributes['first_name'];
    }

    public function lastName(): ?string
    {
        return $this->attributes['last_name'] ?? null;
    }

    public function phone(): string
    {
        return $this->attributes['phone'];
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

    public function role(): int
    {
        return $this->attributes['role_id'];
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}