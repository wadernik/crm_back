<?php

declare(strict_types=1);

namespace App\Services\Dooglys\SalePoint\Sub;

interface SalePointInterface
{
    public function id(): string;

    public function name(): string;

    public function phone(): ?string;

    public function menu(): ?string;

    public function address(): SalePointAddressInterface;
}