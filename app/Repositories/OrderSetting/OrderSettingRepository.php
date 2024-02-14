<?php

declare(strict_types=1);

namespace App\Repositories\OrderSetting;

use App\Models\OrderSetting\OrderSetting;
use App\Models\OrderSetting\OrderSettingTypeEnum;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class OrderSettingRepository extends AbstractRepository implements OrderSettingRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(OrderSetting::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
    }

    public function find(int $id): ?OrderSetting
    {
        /** @var OrderSetting $orderSetting */
        $orderSetting = OrderSetting::query()->find($id);

        return $orderSetting;
    }

    public function findOneByType(OrderSettingTypeEnum $type): ?OrderSetting
    {
        $orderSettings = OrderSetting::query()
            ->where('type', $type->value)
            ->get();

        /** @var OrderSetting $orderSetting */
        $orderSetting = $orderSettings->first();

        return $orderSetting;
    }
}