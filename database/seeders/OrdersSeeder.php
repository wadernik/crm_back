<?php

namespace Database\Seeders;

use App\Models\Dictionary\DictionaryTypeEnum;
use App\Models\Manufacturer\Manufacturer;
use App\Models\Order\OrderStatus;
use App\Models\Seller\Seller;
use App\Models\Unit\UnitEnum;
use App\Models\User\User;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;
use App\Services\Order\ManagerExtension\Normal\OrderCreatorServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use function App\Helpers\Functions\load_service;

class OrdersSeeder extends Seeder
{
    public const ORDERS_LIMIT = 10;

    public function run(): void
    {
        $orderService = load_service(OrderCreatorServiceInterface::class);

        $dictionaryRepository = load_service(DictionaryRepositoryInterface::class);
        $filter = [
            'type' => DictionaryTypeEnum::PRODUCT_TITLE->value,
            'deleted_at' => null,
        ];

        $titles = $dictionaryRepository->findAllBy(['filter' => $filter]);

        for ($i = 1; $i <= self::ORDERS_LIMIT; $i++) {
            $manufacturer = Manufacturer::query()
                ->inRandomOrder()
                ->first();

            $source = Seller::query()
                ->inRandomOrder()
                ->first();

            $seller = Seller::query()
                ->inRandomOrder()
                ->first();

            $user = User::query()
                ->inRandomOrder()
                ->first();

            $now = Carbon::now()->addDays(random_int(0, 3));

            $status = array_rand(OrderStatus::statusCaptions());

            $unit = collect(UnitEnum::cases())->random()->value;

            $attributes = [
                'manufacturer_id' => $manufacturer['id'] ?? 1,
                'source_id' => $source['id'] ?? 1,
                'seller_id' => $seller['id'] ?? 1,
                'user_id' => $user['id'] ?? 1,
                'status' => $status ?? 1,
                'accepted_date' => $now->format('Y-m-d'),
                'order_date' => $now->addDays(random_int(0, 1))->format('Y-m-d'),
                'order_time' => $now->subMinutes(random_int(0, 55)),
                'phone' => '79111111111',
                'items' => [
                    [
                        'title_id' => $titles->random()->id,
                        'name' => Str::random(),
                        'label' => Str::random(),
                        'amount' => random_int(50, 3000),
                        'comment' => Str::random(),
                        'unit_id' => $unit,
                    ],
                ],
            ];

            $orderService->create($attributes);
        }
    }
}