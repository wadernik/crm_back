<?php

declare(strict_types=1);

namespace App\Repositories\Sub;

interface AbstractRepositoryInterface extends FindAllByCriteriaInterface,
                                              ApplyFilterInterface,
                                              ApplySortInterface,
                                              ApplyLimitInterface,
                                              ApplyOffsetInterface,
                                              ApplyWithInterface,
                                              ApplyWithTrashedInterface
{
}