<?php

declare(strict_types=1);

namespace App\Services\OrderSetting\ManagerExtension;

use App\DTOs\OrderSetting\CreateOrderSettingDTOInterface;
use App\Managers\OrderSetting\OrderSettingManagerInterface;
use App\Models\OrderSetting\OrderSetting;
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
        $orderSetting = $this->repository->findOneByTypeId($orderSettingDTO->toArray()['type_id']);

        if (!$orderSetting) {
            $orderSetting = $this->manager->create($orderSettingDTO);
        }

        return $orderSetting;
    }
}