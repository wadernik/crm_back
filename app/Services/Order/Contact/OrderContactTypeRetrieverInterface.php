<?php

declare(strict_types=1);

namespace App\Services\Order\Contact;

interface OrderContactTypeRetrieverInterface
{
    public function get(): array;
}