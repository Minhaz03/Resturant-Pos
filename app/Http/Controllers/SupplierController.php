<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('purchaseOrders');
        if ($request->search) $query->where('name', 'like', '%' . $request->search . '%')->orWhere('phone', 'like', '%' . $request->search . '%');
        $suppliers = $query->latest()->paginate(15);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'company'        => 'nullable|string',
            'phone'          => 'required|string|unique:suppliers',
            'email'          => 'nullable|email',
            'address'        => 'nullable|string',
            'city'           => 'nullable|string',
            'contact_person' => 'nullable|string',
            'tax_number'     => 'nullable|string',
            'payment_terms'  => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
        ]);
        $data['status'] = 'active';
        Supplier::create($data);
        return redirect()->route('suppliers.index')->with('success', 'Supplier created.');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'company'        => 'nullable|string',
            'phone'          => 'required|string|unique:suppliers,phone,' . $supplier->id,
            'email'          => 'nullable|email',
            'address'        => 'nullable|string',
            'city'           => 'nullable|string',
            'contact_person' => 'nullable|string',
            'status'         => 'required|in:active,inactive',
            'payment_terms'  => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
        ]);
        $supplier->update($data);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted.');
    }
}
