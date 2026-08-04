<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $expenseCategories = \App\Models\ExpenseCategory::withCount('expenses')->latest()->paginate(15);
        return view('expenses.categories.index', compact('expenseCategories'));
    }

    public function create()
    {
        return view('expenses.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        \App\Models\ExpenseCategory::create($data);

        return redirect()->route('expense-categories.index')->with('success', 'Expense category created successfully.');
    }

    public function edit(\App\Models\ExpenseCategory $expenseCategory)
    {
        return view('expenses.categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, \App\Models\ExpenseCategory $expenseCategory)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $expenseCategory->update($data);

        return redirect()->route('expense-categories.index')->with('success', 'Expense category updated successfully.');
    }

    public function destroy(\App\Models\ExpenseCategory $expenseCategory)
    {
        if ($expenseCategory->expenses()->count() > 0) {
            return back()->with('error', 'Cannot delete category with associated expenses.');
        }
        
        $expenseCategory->delete();
        return redirect()->route('expense-categories.index')->with('success', 'Expense category deleted successfully.');
    }
}

