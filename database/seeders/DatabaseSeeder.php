<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            PermissionsTableSeeder::class,
            UsersTableSeeder::class,
            ManufacturersTableSeeder::class,
            SellersTableSeeder::class,
        ]);

        $this->call([
            RolePermissionTableSeeder::class,
        ]);
    }
}
