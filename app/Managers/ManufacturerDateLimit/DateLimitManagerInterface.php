<?php

declare(strict_types=1);

namespace App\Managers\ManufacturerDateLimit;

interface DateLimitManagerInterface extends DateLimitCreatorInterface,
                                            DateLimitUpdaterInterface,
                                            DateLimitDeleterInterface
{
}