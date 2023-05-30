<?php

declare(strict_types=1);

namespace App\Managers\Manufacturer;

interface ManufacturerManagerInterface extends ManufacturerCreatorInterface,
                                               ManufacturerUpdaterInterface,
                                               ManufacturerDeleterInterface
{
}