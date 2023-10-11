<?php

declare(strict_types=1);

namespace App\Services\Dooglys\SalePoint\Sub;

interface SalePointAddressInterface
{
    public function name(): ?string;

    public function shortName(): ?string;

    public function latitude(): ?string;

    public function longitude(): ?string;
}