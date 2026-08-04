<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = \App\Models\Expense::with(['category', 'user'])->latest('date')->paginate(15);
        $categories = \App\Models\ExpenseCategory::orderBy('name')->get();
        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = \App\Models\ExpenseCategory::orderBy('name')->get();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount'              => 'required|numeric|min:0',
            'date'                => 'required|date',
            'reference_number'    => 'nullable|string|max:50',
            'note'                => 'nullable|string',
        ]);

        $data['user_id'] = \Illuminate\Support\Facades\Auth::id();

        \App\Models\Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function edit(\App\Models\Expense $expense)
    {
        $categories = \App\Models\ExpenseCategory::orderBy('name')->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, \App\Models\Expense $expense)
    {
        $data = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount'              => 'required|numeric|min:0',
            'date'                => 'required|date',
            'reference_number'    => 'nullable|string|max:50',
            'note'                => 'nullable|string',
        ]);

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(\App\Models\Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}

