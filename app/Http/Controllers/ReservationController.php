<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['tables', 'customer']);
        if ($request->date) $query->whereDate('reservation_date', $request->date);
        if ($request->status) $query->where('status', $request->status);
        $reservations = $query->orderBy('reservation_date')->orderBy('reservation_time')->paginate(15);
        $upcomingCount = Reservation::upcoming()->count();
        $tables = Table::orderBy('table_number')->get();
        return view('reservations.index', compact('reservations', 'upcomingCount', 'tables'));
    }

    public function create()
    {
        $tables = Table::whereIn('status', ['available', 'reserved'])->orderBy('table_number')->get();
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        return view('reservations.create', compact('tables', 'customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string',
            'customer_email' => 'nullable|email',
            'table_ids' => 'nullable|array',
            'table_ids.*' => 'exists:tables,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'guest_count' => 'required|integer|min:1',
            'deposit_amount' => 'nullable|numeric|min:0',
            'deposit_payment_method' => 'nullable|string|in:cash,card,mobile_banking',
            'notes' => 'nullable|string',
        ]);

        $data['reservation_number'] = 'RES-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        $data['status'] = 'pending';
        $data['created_by'] = auth()->id();

        $reservation = Reservation::create($data);

        if (!empty($request->table_ids)) {
            $reservation->tables()->sync($request->table_ids);
            Table::whereIn('id', $request->table_ids)->update(['status' => 'reserved']);
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation #' . $data['reservation_number'] . ' created.');
    }

    public function edit(Reservation $reservation)
    {
        $tables = Table::orderBy('table_number')->get();
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        return view('reservations.edit', compact('reservation', 'tables', 'customers'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string',
            'customer_email' => 'nullable|email',
            'table_ids' => 'nullable|array',
            'table_ids.*' => 'exists:tables,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'guest_count' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,seated,completed,cancelled,no_show',
            'deposit_amount' => 'nullable|numeric|min:0',
            'deposit_payment_method' => 'nullable|string|in:cash,card,mobile_banking',
            'notes' => 'nullable|string',
        ]);

        if ($data['status'] === 'confirmed') $data['confirmed_at'] = now();
        $reservation->update($data);

        if ($request->has('table_ids')) {
            $reservation->tables()->sync($request->table_ids ?: []);
            if (!empty($request->table_ids)) {
                Table::whereIn('id', $request->table_ids)->update(['status' => 'reserved']);
            }
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservation deleted.');
    }
}
