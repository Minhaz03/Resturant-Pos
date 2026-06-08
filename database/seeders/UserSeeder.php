<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@restaurant.com',
                'password' => Hash::make('password'),
                'phone' => '+880 1700-000001',
                'status' => 'active',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Restaurant Owner',
                'email' => 'owner@restaurant.com',
                'password' => Hash::make('password'),
                'phone' => '+880 1700-000002',
                'status' => 'active',
                'role' => 'owner',
            ],
            [
                'name' => 'Ahmed Manager',
                'email' => 'manager@restaurant.com',
                'password' => Hash::make('password'),
                'phone' => '+880 1700-000003',
                'status' => 'active',
                'role' => 'manager',
            ],
            [
                'name' => 'Rahim Cashier',
                'email' => 'cashier@restaurant.com',
                'password' => Hash::make('password'),
                'phone' => '+880 1700-000004',
                'status' => 'active',
                'role' => 'cashier',
            ],
            [
                'name' => 'Karim Waiter',
                'email' => 'waiter@restaurant.com',
                'password' => Hash::make('password'),
                'phone' => '+880 1700-000005',
                'status' => 'active',
                'role' => 'waiter',
            ],
            [
                'name' => 'Chef Jalal',
                'email' => 'kitchen@restaurant.com',
                'password' => Hash::make('password'),
                'phone' => '+880 1700-000006',
                'status' => 'active',
                'role' => 'kitchen_staff',
            ],
            [
                'name' => 'Rafi Rider',
                'email' => 'delivery@restaurant.com',
                'password' => Hash::make('password'),
                'phone' => '+880 1700-000007',
                'status' => 'active',
                'role' => 'delivery_staff',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            $user = User::firstOrCreate(['email' => $userData['email']], $userData);
            $user->syncRoles([$role]);
        }
    }
}
