<?php

declare(strict_types=1);

namespace App\Managers\OrderComposite;

interface OrderCompositeManagerInterface extends OrderCompositeCreatorInterface,
                                                 OrderCompositeUpdaterInterface,
                                                 OrderCompositeDeleterInterface
{
}