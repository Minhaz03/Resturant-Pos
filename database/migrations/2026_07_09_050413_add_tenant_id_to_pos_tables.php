<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'users', 'categories', 'menu_items', 'tables', 'customers',
            'reservations', 'orders', 'order_items', 'payments', 'invoices',
            'employees', 'attendances', 'suppliers', 'inventory_items',
            'inventory_transactions', 'purchase_orders', 'purchase_order_items',
            'delivery_orders', 'coupons', 'loyalty_transactions', 'kitchen_orders',
            'notifications', 'menu_item_ingredients', 'restaurant_settings',
            'business_hours'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users', 'categories', 'menu_items', 'tables', 'customers',
            'reservations', 'orders', 'order_items', 'payments', 'invoices',
            'employees', 'attendances', 'suppliers', 'inventory_items',
            'inventory_transactions', 'purchase_orders', 'purchase_order_items',
            'delivery_orders', 'coupons', 'loyalty_transactions', 'kitchen_orders',
            'notifications', 'menu_item_ingredients', 'restaurant_settings',
            'business_hours'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['tenant_id']);
                    $table->dropColumn('tenant_id');
                });
            }
        }
    }
};
