<?php

declare(strict_types=1);

namespace App\Services\Dooglys\SalePoint\Sub;

final class SalePoint implements SalePointInterface
{
    /**
     * @param array{
     *     id: string,
     *     menu_id: string|null,
     *     name: string,
     *     phone_number: string|null,
     *     address: array{
     *         name: string|null,
     *         short_name: string|null,
     *         lat: string|null,
     *         long: string|null
     *     }|null
     * $salePoint
     */
    public function __construct(private readonly array $salePoint)
    {
    }

    public function id(): string
    {
        return $this->salePoint['id'];
    }

    public function name(): string
    {
        return $this->salePoint['name'];
    }

    public function phone(): ?string
    {
        return $this->salePoint['phone_number'] ?: null;
    }

    public function menu(): ?string
    {
        return $this->salePoint['menu_id'];
    }

    public function address(): SalePointAddressInterface
    {
        return new SalePointAddress($this->salePoint['address']);
    }
}