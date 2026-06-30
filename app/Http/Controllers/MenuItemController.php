<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $query = MenuItem::with('category');
        if ($request->category_id) $query->where('category_id', $request->category_id);
        if ($request->search) $query->where('name', 'like', '%' . $request->search . '%');
        if ($request->status !== null) $query->where('is_available', $request->status);
        $menuItems = $query->latest()->paginate(15);
        $categories = Category::where('status', true)->get();
        return view('menu.index', compact('menuItems', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->orderBy('sort_order')->get();
        return view('menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|max:2048',
            'prep_time' => 'nullable|integer|min:1',
            'unit' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'sku' => 'nullable|string|unique:menu_items',
            'barcode' => 'nullable|string|unique:menu_items',
        ]);

        $data['slug'] = Str::slug($data['name'] . '-' . uniqid());
        $data['is_available'] = $request->boolean('is_available', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['status'] = $request->boolean('status', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu-items', 'public');
        }

        MenuItem::create($data);
        return redirect()->route('menu.index')->with('success', 'Menu item created successfully.');
    }

    public function show(MenuItem $menu)
    {
        $menuItem = $menu;
        return view('menu.show', compact('menuItem'));
    }

    public function edit(MenuItem $menu)
    {
        $menuItem = $menu;
        $categories = Category::where('status', true)->orderBy('sort_order')->get();
        return view('menu.edit', compact('menuItem', 'categories'));
    }

    public function update(Request $request, MenuItem $menu)
    {
        $menuItem = $menu;
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|max:2048',
            'prep_time' => 'nullable|integer|min:1',
            'unit' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'sku' => 'nullable|string|unique:menu_items,sku,' . $menuItem->id,
            'barcode' => 'nullable|string|unique:menu_items,barcode,' . $menuItem->id,
        ]);

        $data['is_available'] = $request->boolean('is_available', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['status'] = $request->boolean('status', true);

        if ($request->hasFile('image')) {
            if ($menuItem->image) Storage::disk('public')->delete($menuItem->image);
            $data['image'] = $request->file('image')->store('menu-items', 'public');
        }

        $menuItem->update($data);
        return redirect()->route('menu.index')->with('success', 'Menu item updated successfully.');
    }

    public function destroy(MenuItem $menu)
    {
        $menuItem = $menu;
        if ($menuItem->image) Storage::disk('public')->delete($menuItem->image);
        $menuItem->delete();
        return redirect()->route('menu.index')->with('success', 'Menu item deleted successfully.');
    }

    public function toggleAvailability(MenuItem $menuItem)
    {
        $menuItem->update(['is_available' => !$menuItem->is_available]);
        return response()->json(['status' => 'success', 'is_available' => $menuItem->is_available]);
    }
}
