<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RestaurantSetting;
use App\Models\BusinessHour;

class RestaurantSettingSeeder extends Seeder
{
    public function run(): void
    {
        RestaurantSetting::firstOrCreate(['slug' => 'main'], [
            'name' => 'The Grand Restaurant',
            'slug' => 'main',
            'tagline' => 'Fine Dining Experience',
            'address' => 'House 12, Road 5, Dhanmondi, Dhaka-1205, Bangladesh',
            'phone' => '+880 1700-000000',
            'email' => 'info@grandrestaurant.com',
            'website' => 'https://grandrestaurant.com',
            'currency' => 'BDT',
            'currency_symbol' => '৳',
            'tax_rate' => 5.00,
            'tax_name' => 'VAT',
            'timezone' => 'Asia/Dhaka',
            'date_format' => 'd/m/Y',
            'time_format' => 'H:i',
            'receipt_footer' => 'Thank you for dining with us! Visit again.',
            'invoice_prefix' => 'INV-',
            'order_prefix' => 'ORD-',
            'loyalty_enabled' => true,
            'loyalty_rate' => 1.00,
            'mail_from_name' => 'The Grand Restaurant',
            'mail_from_address' => 'info@grandrestaurant.com',
        ]);

        $days = [
            ['day_of_week' => 0, 'day_name' => 'Sunday', 'is_open' => true, 'open_time' => '10:00:00', 'close_time' => '22:00:00'],
            ['day_of_week' => 1, 'day_name' => 'Monday', 'is_open' => true, 'open_time' => '10:00:00', 'close_time' => '22:00:00'],
            ['day_of_week' => 2, 'day_name' => 'Tuesday', 'is_open' => true, 'open_time' => '10:00:00', 'close_time' => '22:00:00'],
            ['day_of_week' => 3, 'day_name' => 'Wednesday', 'is_open' => true, 'open_time' => '10:00:00', 'close_time' => '22:00:00'],
            ['day_of_week' => 4, 'day_name' => 'Thursday', 'is_open' => true, 'open_time' => '10:00:00', 'close_time' => '23:00:00'],
            ['day_of_week' => 5, 'day_name' => 'Friday', 'is_open' => true, 'open_time' => '12:00:00', 'close_time' => '23:00:00'],
            ['day_of_week' => 6, 'day_name' => 'Saturday', 'is_open' => true, 'open_time' => '10:00:00', 'close_time' => '23:00:00'],
        ];

        foreach ($days as $day) {
            BusinessHour::firstOrCreate(['day_of_week' => $day['day_of_week']], $day);
        }
    }
}
