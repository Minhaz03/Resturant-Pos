<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
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
