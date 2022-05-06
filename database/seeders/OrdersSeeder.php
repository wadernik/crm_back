<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Seller;
use App\Models\User;
use App\Services\Orders\OrderInstanceService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrdersSeeder extends Seeder
{
    public const ORDERS_LIMIT = 20;

    public function run(): void
    {
        $orderService = app(OrderInstanceService::class);
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

            $status = array_rand(Order::statusCaptions());

            $attributes = [
                'manufacturer_id' => $manufacturer['id'] ?? 1,
                'source_id' => $source['id'] ?? 1,
                'seller_id' => $seller['id'] ?? 1,
                'user_id' => $user['id'] ?? 1,
                'status' => $status ?? 1,
                'accepted_date' => $now->format('Y-m-d'),
                'order_date' => $now->addDays(random_int(0, 1))->format('Y-m-d'),
                'order_time' => $now->subMinutes(random_int(0, 55)),
                'details' => [
                    'name' => Str::random(),
                    'label' => Str::random(),
                    'amount' => random_int(50, 3000),
                    'comment' => Str::random(),
                ],
            ];

            $orderService->createInstance($attributes);
        }
    }
}
