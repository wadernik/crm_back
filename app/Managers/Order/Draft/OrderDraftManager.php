<?php

declare(strict_types=1);

namespace App\Managers\Order\Draft;

use App\Managers\Order\AbstractOrderManager;

final class OrderDraftManager extends AbstractOrderManager implements OrderDraftManagerInterface
{
    public function __construct()
    {
        parent::__construct(draft: true);
    }
}