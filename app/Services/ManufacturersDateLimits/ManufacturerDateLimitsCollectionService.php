<?php

namespace App\Services\ManufacturersDateLimits;

use App\ModelModifiers\ModelFilters\ManufacturerDateLimitFilter;
use App\ModelModifiers\ModelSorts\ManufacturerDateLimitSort;
use App\Models\ManufacturerDateLimit;
use App\Services\AbstractBaseCollectionService;

class ManufacturerDateLimitsCollectionService extends AbstractBaseCollectionService
{
    public function __construct(
        ManufacturerDateLimit $manufacturerDateLimit,
        ManufacturerDateLimitFilter $filter,
        ManufacturerDateLimitSort $sort
    ) {
        $this->modelClass = $manufacturerDateLimit;
        $this->modelFilter = $filter;
        $this->modelSort = $sort;
    }

    /**
     * @param int $manufacturerId
     * @param string $date
     * @param int $limitType
     * @return bool
     */
    public function canAcceptOrderForDate(
        int $manufacturerId,
        string $date,
        int $limitType = ManufacturerDateLimit::STATUS_FULL_STOP
    ): bool {
        $params = [
            'filter' => [
                'manufacturer_id' => $manufacturerId,
                'date' => $date,
                'limit_type' => $limitType,
            ]
        ];

        return empty($this->getInstances(requestParams: $params));
    }
}
