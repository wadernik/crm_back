<?php

declare(strict_types=1);


namespace App\DTOs\User;

final class UserReportDTO implements UserReportDTOInterface
{
    /**
     * @param array{
     *     user_id: string|null,
     *     role_id: string|null,
     *     date_start: string,
     *     date_end: string|null
     * } $attributes
     */
    public function __construct(private array $attributes)
    {
    }

    public function userId(): ?string
    {
        return $this->attributes['user_id'] ?? null;
    }

    public function roleId(): ?string
    {
        return $this->attributes['role_id'] ?? null;
    }

    public function dateStart(): string
    {
        return $this->attributes['date_start'];
    }

    public function dateEnd(): ?string
    {
        return $this->attributes['date_end'] ?? null;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}