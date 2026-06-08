<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Customer::paginate(20)]);
    }

    public function show(Customer $customer)
    {
        return response()->json(['data' => $customer->load(['orders', 'loyaltyTransactions'])]);
    }

    public function store(Request $request) { return response()->json(['message' => 'Use web form.'], 405); }
    public function update(Request $request, Customer $customer) { return response()->json(['message' => 'Use web form.'], 405); }
    public function destroy(Customer $customer) { return response()->json(['message' => 'Not allowed.'], 405); }

    public function search(Request $request)
    {
        $customers = Customer::where('phone', 'like', '%' . $request->q . '%')->orWhere('name', 'like', '%' . $request->q . '%')->take(10)->get(['id', 'name', 'phone', 'email', 'loyalty_points']);
        return response()->json(['data' => $customers]);
    }
}
