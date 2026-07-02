<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('menuItems')->latest()->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100|unique:categories',
            'description'=> 'nullable|string',
            'image'      => 'nullable|image|max:4096',
            'sort_order' => 'nullable|integer',
            'status'     => 'boolean',
        ]);

        $data['slug']   = Str::slug($data['name']);
        $data['status'] = $request->boolean('status', true);

        // Remove image from fillable data — Spatie handles it separately
        unset($data['image']);

        $category = Category::create($data);

        if ($request->hasFile('image')) {
            $category->addMediaFromRequest('image')
                     ->toMediaCollection('image');
        }

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:4096',
            'sort_order'  => 'nullable|integer',
            'status'      => 'boolean',
        ]);

        $data['slug']   = Str::slug($data['name']);
        $data['status'] = $request->boolean('status', true);

        // Remove image from fillable data — Spatie handles it separately
        unset($data['image']);

        $category->update($data);

        if ($request->hasFile('image')) {
            // clearMediaCollection removes the old file before adding new
            $category->clearMediaCollection('image');
            $category->addMediaFromRequest('image')
                     ->toMediaCollection('image');
        }

        // Handle explicit image removal
        if ($request->input('remove_image') == '1') {
            $category->clearMediaCollection('image');
            // Also clear legacy column
            $category->update(['image' => null]);
        }

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->menuItems()->count() > 0) {
            return back()->with('error', 'Cannot delete category with menu items.');
        }
        // Spatie automatically handles media cleanup on delete
        // Also clean up legacy storage image if it exists
        if ($category->image) Storage::disk('public')->delete($category->image);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
