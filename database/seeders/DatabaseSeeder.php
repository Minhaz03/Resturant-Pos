<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a default Tenant for the seeded restaurant data
        $tenant = \App\Models\Tenant::firstOrCreate(
            ['name' => 'The Grand Restaurant'],
            ['is_active' => true]
        );

        // 2. Bind the Tenant to the application container so that BelongsToTenant works during seeding
        app()->instance('tenant', $tenant);

        // 3. Create a default Admin in the admins table for Super Admin panel login
        \App\Models\Admin::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'SaaS Super Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );

        // 4. Run the rest of the seeders
        $this->call([
            RolesAndPermissionsSeeder::class,
            RestaurantSettingSeeder::class,
            UserSeeder::class,
            CategoryAndMenuSeeder::class,
            TableSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
