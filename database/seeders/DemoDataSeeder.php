<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\InventoryItem;
use App\Models\Employee;
use App\Models\Coupon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Customers
        $customers = [
            ['name' => 'Arif Hassan', 'phone' => '01811111111', 'email' => 'arif@example.com', 'address' => 'Dhanmondi, Dhaka'],
            ['name' => 'Sadia Islam', 'phone' => '01911111112', 'email' => 'sadia@example.com', 'address' => 'Gulshan, Dhaka'],
            ['name' => 'Rafiq Ahmed', 'phone' => '01711111113', 'email' => 'rafiq@example.com', 'address' => 'Mirpur, Dhaka'],
            ['name' => 'Nasrin Akter', 'phone' => '01611111114', 'email' => 'nasrin@example.com', 'address' => 'Uttara, Dhaka'],
            ['name' => 'Kamal Hossain', 'phone' => '01511111115', 'email' => 'kamal@example.com', 'address' => 'Motijheel, Dhaka'],
        ];
        foreach ($customers as $customer) {
            Customer::firstOrCreate(['phone' => $customer['phone']], array_merge($customer, ['status' => 'active', 'loyalty_points' => rand(0, 500)]));
        }

        // Suppliers
        $suppliers = [
            ['name' => 'Fresh Farms Ltd', 'company' => 'Fresh Farms Ltd', 'phone' => '02-111111', 'email' => 'freshfarms@example.com', 'contact_person' => 'Mr. Ali', 'address' => 'Farmgate, Dhaka'],
            ['name' => 'Spice World', 'company' => 'Spice World Trading', 'phone' => '02-222222', 'email' => 'spiceworld@example.com', 'contact_person' => 'Mr. Khan', 'address' => 'Old Dhaka'],
            ['name' => 'Meat House BD', 'company' => 'Meat House Bangladesh', 'phone' => '02-333333', 'email' => 'meathouse@example.com', 'contact_person' => 'Mr. Rahim', 'address' => 'Tejgaon, Dhaka'],
        ];
        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(['phone' => $supplier['phone']], array_merge($supplier, ['status' => 'active']));
        }

        // Inventory Items
        $supplier = Supplier::first();
        $inventoryItems = [
            ['name' => 'Basmati Rice', 'sku' => 'INV-001', 'category' => 'Grains', 'unit' => 'kg', 'quantity' => 50, 'min_quantity' => 10, 'unit_cost' => 120],
            ['name' => 'Chicken (Whole)', 'sku' => 'INV-002', 'category' => 'Meat', 'unit' => 'kg', 'quantity' => 30, 'min_quantity' => 10, 'unit_cost' => 280],
            ['name' => 'Mutton', 'sku' => 'INV-003', 'category' => 'Meat', 'unit' => 'kg', 'quantity' => 15, 'min_quantity' => 5, 'unit_cost' => 850],
            ['name' => 'Onion', 'sku' => 'INV-004', 'category' => 'Vegetables', 'unit' => 'kg', 'quantity' => 25, 'min_quantity' => 10, 'unit_cost' => 50],
            ['name' => 'Tomato', 'sku' => 'INV-005', 'category' => 'Vegetables', 'unit' => 'kg', 'quantity' => 20, 'min_quantity' => 8, 'unit_cost' => 60],
            ['name' => 'Cooking Oil', 'sku' => 'INV-006', 'category' => 'Oil', 'unit' => 'litre', 'quantity' => 40, 'min_quantity' => 15, 'unit_cost' => 180],
            ['name' => 'Flour (Maida)', 'sku' => 'INV-007', 'category' => 'Grains', 'unit' => 'kg', 'quantity' => 30, 'min_quantity' => 10, 'unit_cost' => 55],
            ['name' => 'Milk', 'sku' => 'INV-008', 'category' => 'Dairy', 'unit' => 'litre', 'quantity' => 20, 'min_quantity' => 8, 'unit_cost' => 75],
        ];
        foreach ($inventoryItems as $item) {
            $item['supplier_id'] = $supplier?->id;
            $item['total_value'] = $item['quantity'] * $item['unit_cost'];
            $item['status'] = 'active';
            $item['track_inventory'] = true;
            InventoryItem::firstOrCreate(['sku' => $item['sku']], $item);
        }

        // Coupons
        $coupons = [
            ['code' => 'WELCOME10', 'name' => 'Welcome Discount', 'type' => 'percentage', 'value' => 10, 'min_order_amount' => 300, 'max_discount' => 100, 'per_user_limit' => 1, 'status' => true],
            ['code' => 'FLAT50', 'name' => 'Flat 50 Taka Off', 'type' => 'fixed', 'value' => 50, 'min_order_amount' => 500, 'per_user_limit' => 3, 'status' => true],
            ['code' => 'WEEKEND20', 'name' => 'Weekend Special', 'type' => 'percentage', 'value' => 20, 'min_order_amount' => 600, 'max_discount' => 200, 'per_user_limit' => 2, 'status' => true],
        ];
        foreach ($coupons as $coupon) {
            Coupon::firstOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
