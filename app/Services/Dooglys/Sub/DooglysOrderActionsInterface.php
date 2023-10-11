<?php

declare(strict_types=1);

namespace App\Services\Dooglys\Sub;

interface DooglysOrderActionsInterface
{
    /**
     * @param int|string $dateStart
     * @param string     $orderNumber
     *
     * @return array<array{
     *     id: string,
     *     sale_point_id: string,
     *     number: string,
     *     total_cost: int
     * }>
     */
    public function orders(int|string $dateStart, string $orderNumber): array;
}