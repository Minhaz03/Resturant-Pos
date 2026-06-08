<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Support\Str;

class CategoryAndMenuSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Starters', 'description' => 'Light appetizers to start your meal', 'sort_order' => 1],
            ['name' => 'Main Course', 'description' => 'Hearty main dishes', 'sort_order' => 2],
            ['name' => 'Rice & Biryani', 'description' => 'Fragrant rice dishes', 'sort_order' => 3],
            ['name' => 'Breads', 'description' => 'Fresh baked breads and naan', 'sort_order' => 4],
            ['name' => 'Soups', 'description' => 'Hot and cold soups', 'sort_order' => 5],
            ['name' => 'Desserts', 'description' => 'Sweet endings to your meal', 'sort_order' => 6],
            ['name' => 'Beverages', 'description' => 'Refreshing drinks', 'sort_order' => 7],
            ['name' => 'Fast Food', 'description' => 'Quick bites', 'sort_order' => 8],
        ];

        foreach ($categories as $cat) {
            $cat['slug'] = Str::slug($cat['name']);
            $cat['status'] = true;
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $menuItems = [
            // Starters
            ['category' => 'Starters', 'name' => 'Chicken Tikka', 'price' => 280, 'cost_price' => 140, 'prep_time' => 20, 'sku' => 'STR-001'],
            ['category' => 'Starters', 'name' => 'Vegetable Samosa (4 pcs)', 'price' => 120, 'cost_price' => 50, 'prep_time' => 10, 'sku' => 'STR-002'],
            ['category' => 'Starters', 'name' => 'Seekh Kebab', 'price' => 320, 'cost_price' => 160, 'prep_time' => 25, 'sku' => 'STR-003'],
            ['category' => 'Starters', 'name' => 'Shrimp Cocktail', 'price' => 450, 'cost_price' => 200, 'prep_time' => 15, 'sku' => 'STR-004'],

            // Main Course
            ['category' => 'Main Course', 'name' => 'Butter Chicken', 'price' => 380, 'cost_price' => 170, 'prep_time' => 25, 'sku' => 'MC-001'],
            ['category' => 'Main Course', 'name' => 'Mutton Rogan Josh', 'price' => 520, 'cost_price' => 250, 'prep_time' => 35, 'sku' => 'MC-002'],
            ['category' => 'Main Course', 'name' => 'Dal Makhani', 'price' => 220, 'cost_price' => 80, 'prep_time' => 20, 'sku' => 'MC-003'],
            ['category' => 'Main Course', 'name' => 'Fish Curry', 'price' => 420, 'cost_price' => 190, 'prep_time' => 25, 'sku' => 'MC-004'],
            ['category' => 'Main Course', 'name' => 'Prawn Masala', 'price' => 580, 'cost_price' => 280, 'prep_time' => 25, 'sku' => 'MC-005'],

            // Rice & Biryani
            ['category' => 'Rice & Biryani', 'name' => 'Chicken Biryani', 'price' => 320, 'cost_price' => 140, 'prep_time' => 30, 'sku' => 'RB-001'],
            ['category' => 'Rice & Biryani', 'name' => 'Mutton Biryani', 'price' => 450, 'cost_price' => 200, 'prep_time' => 40, 'sku' => 'RB-002'],
            ['category' => 'Rice & Biryani', 'name' => 'Vegetable Fried Rice', 'price' => 220, 'cost_price' => 80, 'prep_time' => 20, 'sku' => 'RB-003'],
            ['category' => 'Rice & Biryani', 'name' => 'Plain Rice', 'price' => 80, 'cost_price' => 25, 'prep_time' => 15, 'sku' => 'RB-004'],

            // Breads
            ['category' => 'Breads', 'name' => 'Butter Naan', 'price' => 60, 'cost_price' => 20, 'prep_time' => 10, 'sku' => 'BR-001'],
            ['category' => 'Breads', 'name' => 'Garlic Naan', 'price' => 80, 'cost_price' => 25, 'prep_time' => 10, 'sku' => 'BR-002'],
            ['category' => 'Breads', 'name' => 'Paratha', 'price' => 50, 'cost_price' => 15, 'prep_time' => 10, 'sku' => 'BR-003'],
            ['category' => 'Breads', 'name' => 'Luchi (4 pcs)', 'price' => 60, 'cost_price' => 20, 'prep_time' => 12, 'sku' => 'BR-004'],

            // Soups
            ['category' => 'Soups', 'name' => 'Chicken Corn Soup', 'price' => 150, 'cost_price' => 60, 'prep_time' => 15, 'sku' => 'SP-001'],
            ['category' => 'Soups', 'name' => 'Tomato Soup', 'price' => 120, 'cost_price' => 45, 'prep_time' => 12, 'sku' => 'SP-002'],
            ['category' => 'Soups', 'name' => 'Hot & Sour Soup', 'price' => 160, 'cost_price' => 65, 'prep_time' => 15, 'sku' => 'SP-003'],

            // Desserts
            ['category' => 'Desserts', 'name' => 'Gulab Jamun (2 pcs)', 'price' => 120, 'cost_price' => 40, 'prep_time' => 5, 'sku' => 'DS-001'],
            ['category' => 'Desserts', 'name' => 'Rasmalai', 'price' => 150, 'cost_price' => 55, 'prep_time' => 5, 'sku' => 'DS-002'],
            ['category' => 'Desserts', 'name' => 'Ice Cream (2 Scoops)', 'price' => 180, 'cost_price' => 70, 'prep_time' => 3, 'sku' => 'DS-003'],
            ['category' => 'Desserts', 'name' => 'Chocolate Lava Cake', 'price' => 250, 'cost_price' => 100, 'prep_time' => 15, 'sku' => 'DS-004'],

            // Beverages
            ['category' => 'Beverages', 'name' => 'Fresh Lime Soda', 'price' => 80, 'cost_price' => 25, 'prep_time' => 3, 'sku' => 'BV-001'],
            ['category' => 'Beverages', 'name' => 'Mango Lassi', 'price' => 120, 'cost_price' => 40, 'prep_time' => 5, 'sku' => 'BV-002'],
            ['category' => 'Beverages', 'name' => 'Mineral Water', 'price' => 30, 'cost_price' => 10, 'prep_time' => 1, 'sku' => 'BV-003'],
            ['category' => 'Beverages', 'name' => 'Soft Drink (Can)', 'price' => 60, 'cost_price' => 30, 'prep_time' => 1, 'sku' => 'BV-004'],
            ['category' => 'Beverages', 'name' => 'Fresh Orange Juice', 'price' => 150, 'cost_price' => 55, 'prep_time' => 5, 'sku' => 'BV-005'],

            // Fast Food
            ['category' => 'Fast Food', 'name' => 'Chicken Burger', 'price' => 220, 'cost_price' => 90, 'prep_time' => 15, 'sku' => 'FF-001'],
            ['category' => 'Fast Food', 'name' => 'French Fries (Regular)', 'price' => 120, 'cost_price' => 40, 'prep_time' => 10, 'sku' => 'FF-002'],
            ['category' => 'Fast Food', 'name' => 'Pizza Margherita (8 inch)', 'price' => 350, 'cost_price' => 140, 'prep_time' => 20, 'sku' => 'FF-003'],
            ['category' => 'Fast Food', 'name' => 'Grilled Sandwich', 'price' => 160, 'cost_price' => 60, 'prep_time' => 12, 'sku' => 'FF-004'],
        ];

        $categoryMap = Category::pluck('id', 'name')->toArray();

        foreach ($menuItems as $item) {
            $categoryName = $item['category'];
            unset($item['category']);

            $item['category_id'] = $categoryMap[$categoryName] ?? 1;
            $item['slug'] = Str::slug($item['name'] . '-' . $item['sku']);
            $item['description'] = 'Freshly prepared ' . strtolower($item['name']) . ' made with finest ingredients.';
            $item['tax_rate'] = 5.00;
            $item['is_available'] = true;
            $item['is_featured'] = in_array($item['sku'], ['STR-001', 'MC-001', 'RB-001', 'DS-004']);
            $item['status'] = true;
            $item['unit'] = 'plate';

            MenuItem::firstOrCreate(['sku' => $item['sku']], $item);
        }
    }
}
