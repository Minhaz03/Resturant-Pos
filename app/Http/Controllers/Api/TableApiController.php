<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableApiController extends Controller
{
    public function index()
    {
        $tables = Table::with('activeOrders')->orderBy('table_number')->get();
        return response()->json(['data' => $tables]);
    }

    public function updateStatus(Request $request, Table $table)
    {
        $request->validate(['status' => 'required|in:available,occupied,reserved,inactive']);
        $table->update(['status' => $request->status]);
        return response()->json(['data' => $table]);
    }
}
