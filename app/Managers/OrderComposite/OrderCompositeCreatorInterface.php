<?php

declare(strict_types=1);

namespace App\Managers\OrderComposite;

use App\DTOs\Order\Composite\OrderCompositeInterface;

interface OrderCompositeCreatorInterface
{
    public function create(OrderCompositeInterface $orderComposite): void;
}