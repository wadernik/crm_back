<?php

declare(strict_types=1);

namespace App\Repositories\User;

interface UserStatusesInterface
{
    /**
     * @return array<array{
     *     id: int,
     *     name: string
     * }>
     */
    public function statuses(): array;
}