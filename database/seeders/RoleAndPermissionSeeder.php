<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'user_create',
            'user_edit',
            'user_delete',
            'user_show',
            'role_create',
            'role_edit',
            'role_delete',
            'role_show',
            'permission_create',
            'permission_edit',
            'permission_delete',
            'permission_show'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo($permissions);

        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            'user_show',
            'user_edit',
            'role_show',
            'permission_show'
        ]);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo([
            'user_show'
        ]);
    }
}
