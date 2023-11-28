<?php

declare(strict_types=1);

namespace App\Managers\Order;

interface BaseOrderManagerInterface extends OrderCreatorInterface,
                                            OrderUpdaterInterface,
                                            OrderDeleterInterface,
                                            OrderRemoveInterface
{
}