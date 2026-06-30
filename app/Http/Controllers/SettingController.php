<?php

namespace App\Http\Controllers;

use App\Models\RestaurantSetting;
use App\Models\BusinessHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = RestaurantSetting::first();
        $settings = $setting; // alias so view can use either name
        $businessHours = BusinessHour::orderBy('day_of_week')->get();
        return view('settings.index', compact('setting', 'settings', 'businessHours'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'tagline' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_name' => 'required|string',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            'receipt_footer' => 'nullable|string',
            'invoice_prefix' => 'required|string',
            'order_prefix' => 'required|string',
            'loyalty_rate' => 'nullable|numeric',
            'logo' => 'nullable|image|max:2048',
        ]);

        $setting = RestaurantSetting::first();
        $data['loyalty_enabled'] = $request->boolean('loyalty_enabled');

        if ($request->hasFile('logo')) {
            if ($setting?->logo) Storage::disk('public')->delete($setting->logo);
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        RestaurantSetting::updateOrCreate(['slug' => 'main'], $data);
        return back()->with('success', 'Settings updated successfully.');
    }

    public function updateBusinessHours(Request $request)
    {
        foreach ($request->hours as $dayId => $hours) {
            BusinessHour::where('id', $dayId)->update([
                'is_open' => isset($hours['is_open']),
                'open_time' => $hours['open_time'],
                'close_time' => $hours['close_time'],
            ]);
        }
        return back()->with('success', 'Business hours updated.');
    }
}
