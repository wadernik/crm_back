<?php

declare(strict_types=1);

namespace App\Services\Dooglys\SalePoint\Sub;

final class SalePointAddress implements SalePointAddressInterface
{
    /**
     * @param array{
     *     name: string|null,
     *     short_name: string|null,
     *     lat: string|null,
     *     long: string|null
     * } $address
     */
    public function __construct(private readonly array $address)
    {
    }

    public function name(): ?string
    {
        return $this->address['name'] ?? null;
    }

    public function shortName(): ?string
    {
        return $this->address['short_name'] ?? null;
    }

    public function latitude(): ?string
    {
        return $this->address['lat'] ?? null;
    }

    public function longitude(): ?string
    {
        return $this->address['long'] ?? null;
    }
}