<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $items = [
            [
                'id' => 1,
                'first_name' => 'Илья',
                'last_name' => 'Закарая',
                'username' => 'ilia',
                'password' => bcrypt('123123'), // password
                // 'remember_token' => Str::random(10),
                'email' => 'chill@stroman.org',
                'phone' => '79111111111',
                'sex' => 1,
                'role_id' => 1,
            ],
            [
                'id' => 2,
                'first_name' => 'Александр',
                'last_name' => 'Столяров',
                'username' => 'stolyar',
                'password' => bcrypt('123123'), // password
                'email' => 'admin@aidadev.ru',
                'phone' => '79111111112',
                'sex' => 1,
                'role_id' => 1,
            ],
            [
                'id' => 3,
                'first_name' => 'Павел',
                'username' => 'pavel',
                'password' => bcrypt('123123'), // password
                'email' => 'freepzu@gmail.com',
                'phone' => '79111111115',
                'sex' => 1,
                'role_id' => 1,
            ],
        ];

        foreach ($items as $item) {
            User::query()->firstOrCreate(['id' => $item['id']], $item);
        }
    }
}