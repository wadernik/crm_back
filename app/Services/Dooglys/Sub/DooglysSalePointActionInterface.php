<?php

declare(strict_types=1);

namespace App\Services\Dooglys\Sub;

interface DooglysSalePointActionInterface
{
    /**
     * @return array<array{
     *     id: string,
     *     menu_id: string,
     *     name: string,
     *     phone_number: string|null,
     *     address: array{
     *         name: string,
     *         short_name: string,
     *         lat: float,
     *         long: float
     *     }
     * }>
     */
    public function salePoints(): array;
}