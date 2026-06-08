<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Category;

class MenuApiController extends Controller
{
    public function index()
    {
        $items = MenuItem::active()->with('category')->get();
        return response()->json(['data' => $items]);
    }

    public function show($id)
    {
        $item = MenuItem::active()->with('category')->findOrFail($id);
        return response()->json(['data' => $item]);
    }

    public function categories()
    {
        $categories = Category::where('status', true)->with('activeMenuItems')->orderBy('sort_order')->get();
        return response()->json(['data' => $categories]);
    }
}
