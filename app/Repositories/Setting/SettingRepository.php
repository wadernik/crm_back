<?php

declare(strict_types=1);

namespace App\Repositories\Setting;

use App\Models\Setting\Setting;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

final class SettingRepository extends AbstractRepository implements SettingRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Setting::class);
    }

    public function addExtraFilter(Builder $builder, array &$criteria): void
    {
    }

    public function find(int $id): ?Setting
    {
        /** @var Setting $setting */
        $setting = Setting::query()->find($id);

        return $setting;
    }

    public function findOneByTypeId(int $typeId): ?Setting
    {
        $settings = Setting::query()
            ->where('type_id', $typeId)
            ->get();

        /** @var Setting $setting */
        $setting = $settings->first();

        return $setting;
    }
}