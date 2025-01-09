<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First create roles and permissions
        $this->call(RoleAndPermissionSeeder::class);
        
        // Then create users and assign roles
        $this->call(UserSeeder::class);
    }
}
