<?php

namespace Database\Seeders;

use App\Models\Permission\Permission;
use App\Models\Role\Role;
use App\Models\Role\RoleInterface;
use Illuminate\Database\Seeder;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $permissions = Permission::all();

        /** @var RoleInterface $adminRole */
        $adminRole = Role::query()->find(1);
        $adminRole->permissions()->sync($permissions);
    }
}