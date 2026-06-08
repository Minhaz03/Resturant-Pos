<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\MenuItem;
use App\Models\Employee;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function salesIndex(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->toDateString();
        $groupBy = $request->group_by ?? 'day';

        $rawOrders = Order::whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where('status', 'completed')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(subtotal) as revenue'),
                DB::raw('SUM(tax_amount) as tax'),
                DB::raw('SUM(discount_amount) as discount'),
                DB::raw('SUM(total_amount) as total')
            )->groupBy(DB::raw('DATE(created_at)'))->orderBy('date')->get();

        $salesData = $rawOrders->map(fn($r) => [
            'period' => $r->date,
            'orders' => $r->orders,
            'revenue' => $r->revenue,
            'tax' => $r->tax,
            'discount' => $r->discount,
            'net' => $r->total,
        ])->toArray();

        $summary = [
            'total_orders' => $rawOrders->sum('orders'),
            'total_revenue' => $rawOrders->sum('revenue'),
            'total_tax' => $rawOrders->sum('tax'),
            'total_discount' => $rawOrders->sum('discount'),
            'avg_order_value' => $rawOrders->sum('orders') > 0 ? $rawOrders->sum('revenue') / $rawOrders->sum('orders') : 0,
        ];

        $paymentMethods = Payment::join('orders', 'payments.order_id', '=', 'orders.id')
            ->whereBetween(DB::raw('DATE(orders.created_at)'), [$from, $to])
            ->where('payments.status', 'completed')
            ->select('payments.method', DB::raw('COUNT(*) as count'), DB::raw('SUM(payments.amount) as total'))
            ->groupBy('payments.method')->get();

        return view('reports.sales', compact('salesData', 'summary', 'paymentMethods', 'from', 'to'));
    }

    public function salesData(Request $request)
    {
        return $this->salesIndex($request);
    }

    public function exportSalesPdf(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->toDateString();

        $orders = Order::whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where('status', 'completed')
            ->with(['customer', 'items'])->latest()->get();

        $summary = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_amount'),
            'total_tax' => $orders->sum('tax_amount'),
            'total_discount' => $orders->sum('discount_amount'),
            'avg_order_value' => $orders->count() > 0 ? $orders->sum('total_amount') / $orders->count() : 0,
        ];
        $salesData = $orders->map(fn($o) => [
            'period' => $o->created_at->format('d M Y'),
            'orders' => 1,
            'revenue' => $o->subtotal,
            'tax' => $o->tax_amount,
            'discount' => $o->discount_amount,
            'net' => $o->total_amount,
        ])->toArray();
        $restaurantName = \App\Models\RestaurantSetting::getValue('name', 'The Grand Restaurant');

        $pdf = Pdf::loadView('reports.pdf.sales', compact('salesData', 'summary', 'from', 'to', 'restaurantName'));
        return $pdf->download('sales-report-' . $from . '-to-' . $to . '.pdf');
    }

    public function inventoryReport()
    {
        $items = InventoryItem::with('supplier')->get();
        $stats = [
            'total_items' => $items->count(),
            'total_value' => $items->sum('total_value'),
            'low_stock' => $items->filter(fn($i) => $i->isLowStock())->count(),
            'out_of_stock' => $items->where('quantity', 0)->count(),
        ];
        return view('reports.inventory', compact('items', 'stats'));
    }

    public function customerReport(Request $request)
    {
        $customers = Customer::orderByDesc('total_spent')->paginate(20);
        $stats = [
            'total' => Customer::count(),
            'active_this_month' => Customer::whereHas('orders', fn($q) => $q->whereMonth('created_at', now()->month))->count(),
            'total_points' => Customer::sum('loyalty_points'),
            'avg_spend' => Customer::avg('total_spent') ?? 0,
        ];
        return view('reports.customers', compact('customers', 'stats'));
    }

    public function taxReport(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->toDateString();

        $rawTax = Order::whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where('status', 'completed')
            ->select('order_number', DB::raw('DATE(created_at) as date'), 'subtotal', 'tax_rate', 'tax_amount', 'total_amount')
            ->orderBy('created_at')->get();

        $taxData = $rawTax->map(fn($r) => [
            'date' => $r->date,
            'order_number' => $r->order_number,
            'subtotal' => $r->subtotal,
            'tax_rate' => $r->tax_rate ?? 0,
            'tax_amount' => $r->tax_amount,
            'total' => $r->total_amount,
        ])->toArray();

        $summary = [
            'total_revenue' => $rawTax->sum('total_amount'),
            'total_tax' => $rawTax->sum('tax_amount'),
            'taxable_orders' => $rawTax->where('tax_amount', '>', 0)->count(),
        ];

        return view('reports.tax', compact('taxData', 'summary', 'from', 'to'));
    }
}
