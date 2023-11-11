<?php

namespace Database\Seeders;

use Database\Seeders\Board\BoardTableSeeder;
use Database\Seeders\Board\GroupTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            PermissionSectionsTableSeeder::class,
            PermissionsTableSeeder::class,
            UsersTableSeeder::class,
            ManufacturersTableSeeder::class,
            SellersTableSeeder::class,
            DictionariesTableSeeder::class,
            // Task Board
            BoardTableSeeder::class,
            GroupTableSeeder::class,
        ]);

        $this->call([
            RolePermissionTableSeeder::class,
        ]);
    }
}