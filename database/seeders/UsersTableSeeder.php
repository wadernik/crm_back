<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'id' => 1,
                'name' => 'Илья',
                'username' => 'ilia',
                'password' => bcrypt('kavkazdag'), // password
                'remember_token' => Str::random(10),
                'email' => 'chill@stroman.org',
                'phone' => '79111111111',
                'role_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Рандом кассир',
                'username' => 'cashier_1',
                'password' => bcrypt('#cashier1'), // password
                'remember_token' => Str::random(10),
                'email' => 'chashier@stroman.org',
                'phone' => '79111111112',
                'role_id' => 2,
            ],
        ];

        foreach ($items as $item) {
            User::firstOrCreate(['id' => $item['id']], $item);
        }
    }
}
