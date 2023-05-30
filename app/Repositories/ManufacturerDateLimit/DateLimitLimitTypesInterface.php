<?php

declare(strict_types=1);

namespace App\Repositories\ManufacturerDateLimit;

interface DateLimitLimitTypesInterface
{
    /**
     * @return array<array{
     *     id: int,
     *     name: string
     * }>
     */
    public function limitTypes(): array;
}