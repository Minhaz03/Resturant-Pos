<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Coupon;
use App\Models\KitchenOrder;
use App\Models\Invoice;
use App\Models\DeliveryOrder;
use App\Models\RestaurantSetting;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $categories = Category::with('activeMenuItems')->where('status', true)->orderBy('sort_order')->get();
        $tables = Table::all()->sortBy('table_number');
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $setting = RestaurantSetting::first();
        return view('pos.index', compact('categories', 'tables', 'customers', 'setting'));
    }

    public function processOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'payment_status' => 'nullable|in:paid,unpaid',
            'payment_method' => 'required_if:payment_status,paid|in:cash,card,mobile_banking,split',
            'payment_amount' => 'required_if:payment_status,paid|numeric|min:0',
            'reservation_id' => 'nullable|exists:reservations,id',
        ]);

        DB::beginTransaction();
        try {
            $paymentStatus = $request->payment_status ?? 'paid';
            $setting = RestaurantSetting::first();
            $orderNumber = ($setting->order_prefix ?? 'ORD-') . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $subtotal = 0; $taxAmount = 0; $items = [];

            foreach ($request->items as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                $price = $menuItem->effective_price;
                $qty = $item['quantity'];
                $tax = round($price * $qty * $menuItem->tax_rate / 100, 2);
                $itemSubtotal = round($price * $qty, 2);
                $subtotal += $itemSubtotal;
                $taxAmount += $tax;
                $items[] = [
                    'menu_item_id' => $menuItem->id, 'item_name' => $menuItem->name,
                    'unit_price' => $price, 'quantity' => $qty,
                    'tax_rate' => $menuItem->tax_rate, 'tax_amount' => $tax,
                    'subtotal' => $itemSubtotal + $tax, 'status' => 'pending',
                ];
            }

            $couponDiscount = 0;
            if ($request->coupon_code) {
                $coupon = Coupon::where('code', $request->coupon_code)->where('status', true)->first();
                if ($coupon && $coupon->isValid()) {
                    $couponDiscount = $coupon->calculateDiscount($subtotal + $taxAmount);
                    $coupon->increment('used_count');
                }
            }

            $loyaltyDiscount = 0;
            $loyaltyPointsUsed = 0;
            if ($request->loyalty_points_used && $request->customer_id) {
                $customer = Customer::find($request->customer_id);
                $loyaltyPointsUsed = min($request->loyalty_points_used, $customer?->loyalty_points ?? 0);
                $loyaltyDiscount = $loyaltyPointsUsed * 0.5;
            }

            $reservationDeposit = 0;
            if ($request->reservation_id) {
                $reservation = Reservation::find($request->reservation_id);
                if ($reservation) {
                    $reservationDeposit = $reservation->deposit_amount ?? 0;
                    if ($reservation->status === 'pending' || $reservation->status === 'confirmed') {
                        $reservation->update(['status' => 'seated']);
                    }
                }
            }

            $total = max(0, $subtotal + $taxAmount - $couponDiscount - $loyaltyDiscount - $reservationDeposit);

            $activeOrder = null;
            $tableIds = $request->table_ids ? (is_array($request->table_ids) ? $request->table_ids : [$request->table_ids]) : [];
            if (!empty($tableIds) && ($request->order_type ?? 'dine_in') === 'dine_in') {
                $activeOrder = Order::whereHas('tables', function ($q) use ($tableIds) {
                    $q->whereIn('tables.id', $tableIds);
                })->whereNotIn('status', ['completed', 'cancelled'])->first();
            }

            if ($activeOrder && $paymentStatus === 'paid') {
                return response()->json(['success' => false, 'message' => 'This table has an open order. Please use "Send to Kitchen (Hold)" to add these items, then settle the full bill from the Orders page.']);
            }

            if ($activeOrder) {
                $activeOrder->update([
                    'subtotal' => $activeOrder->subtotal + $subtotal,
                    'tax_amount' => $activeOrder->tax_amount + $taxAmount,
                    'total_amount' => $activeOrder->total_amount + $total,
                ]);

                foreach ($items as $item) {
                    $orderItem = $activeOrder->items()->create(array_merge($item, ['status' => 'pending']));
                    KitchenOrder::create(['order_id' => $activeOrder->id, 'order_item_id' => $orderItem->id, 'status' => 'pending']);
                }

                if ($activeOrder->invoice) {
                    $activeOrder->invoice->update([
                        'subtotal' => $activeOrder->invoice->subtotal + $subtotal,
                        'tax_amount' => $activeOrder->invoice->tax_amount + $taxAmount,
                        'total_amount' => $activeOrder->invoice->total_amount + $total,
                    ]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'order_id' => $activeOrder->id,
                    'order_number' => $activeOrder->order_number,
                    'total' => (float) $activeOrder->total_amount,
                    'message' => 'Items added to existing order.'
                ]);
            }

            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $request->customer_id,
                'cashier_id' => auth()->id(),
                'type' => $request->order_type ?? 'dine_in',
                'status' => 'confirmed',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $request->discount ?? 0,
                'total_amount' => $total,
                'coupon_code' => $request->coupon_code,
                'coupon_discount' => $couponDiscount,
                'loyalty_points_used' => $loyaltyPointsUsed,
                'loyalty_points_earned' => (int)($total / 100),
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            foreach ($items as $item) {
                $orderItem = $order->items()->create(array_merge($item, ['status' => 'pending']));
                KitchenOrder::create(['order_id' => $order->id, 'order_item_id' => $orderItem->id, 'status' => 'pending']);
            }

            if ($paymentStatus === 'paid') {
                $paymentNumber = 'PAY-' . date('Ymd') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
                Payment::create([
                    'order_id' => $order->id,
                    'payment_number' => $paymentNumber,
                    'amount' => $total,
                    'method' => $request->payment_method,
                    'status' => 'completed',
                    'change_amount' => max(0, $request->payment_amount - $total),
                    'split_details' => $request->split_details,
                    'processed_by' => auth()->id(),
                    'paid_at' => now(),
                ]);
            }

            $invoiceNumber = ($setting->invoice_prefix ?? 'INV-') . date('Ymd') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            Invoice::create(['order_id' => $order->id, 'invoice_number' => $invoiceNumber, 'subtotal' => $subtotal, 'tax_amount' => $taxAmount, 'discount_amount' => $couponDiscount, 'total_amount' => $total, 'status' => $paymentStatus === 'paid' ? 'paid' : 'issued', 'issued_at' => now(), 'paid_at' => $paymentStatus === 'paid' ? now() : null, 'created_by' => auth()->id()]);

            if (!empty($tableIds)) {
                $order->tables()->sync($tableIds);
                if (($request->order_type ?? 'dine_in') !== 'dine_in') {
                    Table::whereIn('id', $tableIds)->update(['status' => 'available']);
                } else {
                    Table::whereIn('id', $tableIds)->update(['status' => 'occupied']);
                }
            }

            // Auto-create DeliveryOrder if order type is delivery
            if (($request->order_type ?? 'dine_in') === 'delivery') {
                $address = $request->delivery_address;
                $phone = $request->delivery_phone;
                
                if (empty($address) && $request->customer_id) {
                    $customer = Customer::find($request->customer_id);
                    $address = $customer?->address;
                    if (empty($phone)) $phone = $customer?->phone;
                }

                DeliveryOrder::create([
                    'order_id'         => $order->id,
                    'status'           => 'pending',
                    'tracking_code'    => 'TRK-' . strtoupper(str()->random(8)),
                    'delivery_address' => $address ?: 'Address not provided',
                    'delivery_phone'   => $phone ?: 'Phone not provided',
                    'delivery_notes'   => $request->delivery_notes,
                ]);
            }

            if ($request->customer_id) {
                $customer = Customer::find($request->customer_id);
                if ($customer && $loyaltyPointsUsed > 0) {
                    $customer->decrement('loyalty_points', $loyaltyPointsUsed);
                }
                if ($customer && $total > 0) {
                    $customer->increment('loyalty_points', (int)($total / 100));
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $orderNumber,
                'total' => $total,
                'change' => max(0, ($request->payment_amount ?? 0) - $total)
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine()], 500);
        }
    }

    public function searchProduct(Request $request)
    {
        $query = $request->get('q', '');
        $items = MenuItem::active()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%")
                    ->orWhere('barcode', $query);
            })
            ->with('category')->take(10)->get();
        return response()->json($items);
    }

    public function validateCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        if (!$coupon || !$coupon->isValid()) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired coupon']);
        }
        return response()->json(['valid' => true, 'type' => $coupon->type, 'value' => $coupon->value, 'name' => $coupon->name]);
    }

    public function getActiveReservations()
    {
        $reservations = Reservation::with(['customer', 'tables'])
            ->whereDate('reservation_date', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('reservation_time')
            ->get();
        return response()->json($reservations);
    }
}
