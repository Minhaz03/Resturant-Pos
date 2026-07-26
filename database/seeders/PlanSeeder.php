<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic Plan',
                'description' => 'Perfect for small restaurants getting started. Includes basic POS features, inventory tracking, and up to 5 employees.',
                'price' => 1500.00,
                'billing_cycle' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => 'Pro Plan',
                'description' => 'Ideal for growing businesses. Includes all Basic features, plus KDS, advanced reporting, and up to 20 employees.',
                'price' => 3000.00,
                'billing_cycle' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise Plan',
                'description' => 'For large or multi-location restaurants. Unlimited employees, dedicated support, and custom integrations.',
                'price' => 5000.00,
                'billing_cycle' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => 'Yearly Pro Plan',
                'description' => 'Save 20% with annual billing. Includes all Pro Plan features.',
                'price' => 30000.00, // 3000 * 12 = 36000, discounted to 30000
                'billing_cycle' => 'yearly',
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::firstOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
