<?php

declare(strict_types=1);

namespace App\Integration\Dooglys\Sub;

interface ApiClientOptionsInterface extends ApiClientRequestInterface,
                                            ApiClientMethodInterface,
                                            ApiClientParamsInterface,
                                            ApiClientExecuteInterface
{
}