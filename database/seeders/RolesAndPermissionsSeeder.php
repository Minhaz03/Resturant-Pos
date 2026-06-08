<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dashboard
            'view dashboard',

            // Restaurant Settings
            'view settings', 'edit settings',

            // Category Management
            'view categories', 'create categories', 'edit categories', 'delete categories',

            // Menu Management
            'view menu', 'create menu', 'edit menu', 'delete menu',

            // Table Management
            'view tables', 'create tables', 'edit tables', 'delete tables',

            // Reservation Management
            'view reservations', 'create reservations', 'edit reservations', 'delete reservations',

            // Order Management
            'view orders', 'create orders', 'edit orders', 'delete orders', 'cancel orders',

            // Kitchen Display
            'view kitchen', 'update kitchen status',

            // POS
            'access pos',

            // Customer Management
            'view customers', 'create customers', 'edit customers', 'delete customers',

            // Employee Management
            'view employees', 'create employees', 'edit employees', 'delete employees',

            // Attendance
            'view attendance', 'manage attendance',

            // Inventory
            'view inventory', 'create inventory', 'edit inventory', 'delete inventory', 'adjust inventory',

            // Supplier Management
            'view suppliers', 'create suppliers', 'edit suppliers', 'delete suppliers',

            // Purchase Orders
            'view purchases', 'create purchases', 'edit purchases', 'delete purchases',

            // Billing & Payments
            'view payments', 'process payments', 'refund payments',

            // Delivery
            'view delivery', 'manage delivery', 'assign delivery',

            // Coupon Management
            'view coupons', 'create coupons', 'edit coupons', 'delete coupons',

            // Reports
            'view reports', 'export reports',

            // User Management
            'view users', 'create users', 'edit users', 'delete users',

            // Notifications
            'view notifications',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        $owner = Role::firstOrCreate(['name' => 'owner']);
        $owner->syncPermissions(Permission::whereNotIn('name', ['create users', 'edit users', 'delete users'])->get());

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions([
            'view dashboard', 'view categories', 'create categories', 'edit categories',
            'view menu', 'create menu', 'edit menu',
            'view tables', 'create tables', 'edit tables',
            'view reservations', 'create reservations', 'edit reservations',
            'view orders', 'create orders', 'edit orders', 'cancel orders',
            'view kitchen', 'update kitchen status',
            'access pos',
            'view customers', 'create customers', 'edit customers',
            'view employees', 'view attendance', 'manage attendance',
            'view inventory', 'create inventory', 'edit inventory', 'adjust inventory',
            'view suppliers', 'view purchases', 'create purchases',
            'view payments', 'process payments',
            'view delivery', 'manage delivery', 'assign delivery',
            'view coupons', 'create coupons', 'edit coupons',
            'view reports', 'export reports',
            'view notifications',
        ]);

        $cashier = Role::firstOrCreate(['name' => 'cashier']);
        $cashier->syncPermissions([
            'view dashboard', 'view orders', 'create orders', 'edit orders',
            'access pos', 'view customers', 'create customers',
            'view payments', 'process payments',
            'view reservations', 'create reservations',
            'view menu', 'view categories', 'view tables',
            'view coupons', 'view notifications',
        ]);

        $waiter = Role::firstOrCreate(['name' => 'waiter']);
        $waiter->syncPermissions([
            'view dashboard', 'view orders', 'create orders', 'edit orders',
            'view tables', 'view menu', 'view categories',
            'view customers', 'create customers',
            'view reservations', 'create reservations',
            'view notifications',
        ]);

        $kitchen = Role::firstOrCreate(['name' => 'kitchen_staff']);
        $kitchen->syncPermissions([
            'view dashboard', 'view kitchen', 'update kitchen status',
            'view orders', 'view menu', 'view notifications',
        ]);

        $delivery = Role::firstOrCreate(['name' => 'delivery_staff']);
        $delivery->syncPermissions([
            'view dashboard', 'view delivery', 'manage delivery',
            'view orders', 'view notifications',
        ]);

        $customer = Role::firstOrCreate(['name' => 'customer']);
        $customer->syncPermissions([
            'view menu', 'view categories',
        ]);
    }
}
