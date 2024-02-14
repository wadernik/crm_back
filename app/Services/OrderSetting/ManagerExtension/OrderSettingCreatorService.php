<?php

declare(strict_types=1);

namespace App\Services\OrderSetting\ManagerExtension;

use App\DTOs\OrderSetting\CreateOrderSettingDTOInterface;
use App\Managers\OrderSetting\OrderSettingManagerInterface;
use App\Models\OrderSetting\OrderSetting;
use App\Models\OrderSetting\OrderSettingTypeEnum;
use App\Repositories\OrderSetting\OrderSettingRepositoryInterface;

final class OrderSettingCreatorService implements OrderSettingCreatorServiceInterface
{
    public function __construct(
        private readonly OrderSettingRepositoryInterface $repository,
        private readonly OrderSettingManagerInterface $manager
    )
    {
    }

    public function create(CreateOrderSettingDTOInterface $orderSettingDTO): OrderSetting
    {
        $orderSetting = $this->repository->findOneByType(OrderSettingTypeEnum::tryFrom($orderSettingDTO->toArray()['type']));

        if (!$orderSetting) {
            $orderSetting = $this->manager->create($orderSettingDTO);
        }

        return $orderSetting;
    }
}