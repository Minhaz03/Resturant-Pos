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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $categories = Category::with('activeMenuItems')->where('status', true)->orderBy('sort_order')->get();
        $tables = Table::available()->orderBy('table_number')->get();
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $setting = RestaurantSetting::first();
        return view('pos.index', compact('categories', 'tables', 'customers', 'setting'));
    }

    public function processOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'payment_method' => 'required|in:cash,card,mobile_banking,split',
            'payment_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
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

            $total = max(0, $subtotal + $taxAmount - $couponDiscount - $loyaltyDiscount);

            $order = Order::create([
                'order_number' => $orderNumber,
                'table_id' => $request->table_id,
                'customer_id' => $request->customer_id,
                'cashier_id' => auth()->id(),
                'type' => $request->order_type ?? 'dine_in',
                'status' => 'completed',
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
                'completed_at' => now(),
            ]);

            foreach ($items as $item) {
                $orderItem = $order->items()->create(array_merge($item, ['status' => 'served']));
                KitchenOrder::create(['order_id' => $order->id, 'order_item_id' => $orderItem->id, 'status' => 'ready', 'completed_at' => now()]);
            }

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

            $invoiceNumber = ($setting->invoice_prefix ?? 'INV-') . date('Ymd') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            Invoice::create(['order_id' => $order->id, 'invoice_number' => $invoiceNumber, 'subtotal' => $subtotal, 'tax_amount' => $taxAmount, 'discount_amount' => $couponDiscount, 'total_amount' => $total, 'status' => 'paid', 'issued_at' => now(), 'paid_at' => now(), 'created_by' => auth()->id()]);

            if ($request->table_id) Table::find($request->table_id)?->update(['status' => 'available']);

            // Auto-create DeliveryOrder if order type is delivery
            if (($request->order_type ?? 'dine_in') === 'delivery') {
                DeliveryOrder::create([
                    'order_id'         => $order->id,
                    'status'           => 'pending',
                    'delivery_address' => $request->delivery_address,
                    'delivery_notes'   => $request->delivery_notes,
                ]);
            }

            if ($request->customer_id) {
                $customer = Customer::find($request->customer_id);
                $customer?->increment('total_orders');
                $customer?->increment('total_spent', $total);
                if ($loyaltyPointsUsed > 0) $customer?->redeemLoyaltyPoints($loyaltyPointsUsed, $order->id);
                $pointsEarned = (int)($total / 100);
                if ($pointsEarned > 0) $customer?->addLoyaltyPoints($pointsEarned, $order->id, 'Earned from order #' . $orderNumber);
            }

            DB::commit();
            return response()->json(['success' => true, 'order_id' => $order->id, 'order_number' => $orderNumber, 'total' => $total, 'change' => max(0, $request->payment_amount - $total)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
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
}
