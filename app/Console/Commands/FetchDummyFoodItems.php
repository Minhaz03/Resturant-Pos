<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class FetchDummyFoodItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-dummy-food';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch dummy food items from TheMealDB and seed the database for all tenants.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching categories from TheMealDB...');
        $categoriesResponse = Http::get('https://www.themealdb.com/api/json/v1/1/categories.php');
        
        if (!$categoriesResponse->successful()) {
            $this->error('Failed to fetch categories.');
            return;
        }

        $apiCategories = collect($categoriesResponse->json('categories'))->take(5); // Limit to 5 categories

        $tenants = Tenant::all();
        if ($tenants->isEmpty()) {
            $this->warn('No tenants found. Please create a tenant first.');
            return;
        }

        foreach ($tenants as $tenant) {
            $this->info("Processing Tenant: {$tenant->name}");
            
            // Set the tenant in the container so global scopes and traits work correctly
            app()->instance('tenant', $tenant);

            foreach ($apiCategories as $apiCategory) {
                $categoryName = $apiCategory['strCategory'];
                $categoryDesc = Str::limit($apiCategory['strCategoryDescription'], 200);
                $categoryThumb = $apiCategory['strCategoryThumb'];

                $category = Category::firstOrCreate(
                    ['slug' => Str::slug($categoryName) . '-' . $tenant->id],
                    [
                        'name' => $categoryName,
                        'description' => $categoryDesc,
                        'status' => true,
                        'sort_order' => 0,
                    ]
                );

                if ($category->wasRecentlyCreated && $categoryThumb) {
                    try {
                        $category->addMediaFromUrl($categoryThumb)->toMediaCollection('image');
                    } catch (\Exception $e) {
                        $this->warn("Failed to download image for category {$categoryName}");
                    }
                }

                $this->line("  Category: {$categoryName}");

                // Fetch meals for this category
                $mealsResponse = Http::get("https://www.themealdb.com/api/json/v1/1/filter.php?c={$categoryName}");
                
                if ($mealsResponse->successful() && $mealsResponse->json('meals')) {
                    $meals = collect($mealsResponse->json('meals'))->take(5); // Limit to 5 meals

                    foreach ($meals as $meal) {
                        $mealName = $meal['strMeal'];
                        $mealThumb = $meal['strMealThumb'];
                        $mealId = $meal['idMeal'];

                        $menuItem = MenuItem::firstOrCreate(
                            [
                                'slug' => Str::slug($mealName . '-' . $mealId) . '-' . $tenant->id
                            ],
                            [
                                'category_id' => $category->id,
                                'name' => $mealName,
                                'sku' => 'SKU-' . $mealId . '-' . $tenant->id,
                                'barcode' => $mealId . '-' . $tenant->id,
                                'description' => "Delicious {$mealName} prepared fresh.",
                                'price' => rand(100, 500) + 0.99,
                                'cost_price' => rand(50, 100),
                                'status' => true,
                                'is_available' => true,
                                'is_featured' => rand(0, 1) == 1,
                                'prep_time' => rand(10, 45),
                            ]
                        );

                        if ($menuItem->wasRecentlyCreated && $mealThumb) {
                            try {
                                $menuItem->addMediaFromUrl($mealThumb)->toMediaCollection('image');
                                $this->line("    Added Meal: {$mealName}");
                            } catch (\Exception $e) {
                                $this->warn("    Failed to download image for meal {$mealName}");
                            }
                        }
                    }
                }
            }
        }

        $this->info('Dummy food data fetched and populated successfully.');
    }
}
