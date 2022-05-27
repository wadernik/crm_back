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
            [
                'id' => 5,
                'first_name' => 'Пекарь',
                'username' => 'pekar_1',
                'password' => bcrypt('#pekar1'), // password
                'email' => 'pekar@stroman.org',
                'phone' => '79111111114',
                'sex' => 2,
                'role_id' => 4,
            ],
        ];

        foreach ($items as $item) {
            User::firstOrCreate(['id' => $item['id']], $item);
        }
    }
}
