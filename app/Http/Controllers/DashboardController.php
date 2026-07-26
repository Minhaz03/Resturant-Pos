<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\MenuItem;
use App\Models\Table;
use App\Models\Employee;
use App\Models\InventoryItem;
use App\Models\KitchenOrder;
use App\Models\DeliveryOrder;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Today's stats
        $todaySales = Order::today()->where('status', 'completed')->sum('total_amount');
        $todayOrders = Order::today()->count();
        $todayNewCustomers = Customer::whereDate('created_at', today())->count();

        // Weekly sales
        $weeklySales = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'completed')->sum('total_amount');

        // Monthly sales
        $monthlySales = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')->sum('total_amount');

        // Active tables
        $activeTables = Table::where('status', 'occupied')->count();
        $totalTables = Table::count();
        $availableTables = Table::where('status', 'available')->count();

        // Kitchen orders pending
        $kitchenPending = KitchenOrder::whereIn('status', ['pending', 'preparing'])->count();

        // Pending deliveries
        $pendingDeliveries = DeliveryOrder::whereIn('status', ['pending', 'assigned', 'picked_up', 'on_way'])->count();

        // Recent orders
        $recentOrders = Order::with(['tables', 'customer', 'items'])
            ->latest()->take(10)->get();

        // Active orders
        $activeOrders = Order::active()->with(['tables', 'customer'])->latest()->get();

        // Top selling items (last 30 days)
        $topSellingItems = DB::table('order_items')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', now()->subDays(30))
            ->where('orders.status', 'completed')
            ->select('menu_items.name', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderByDesc('total_qty')
            ->take(5)->get();

        // Revenue chart data (last 7 days)
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenueChart[] = [
                'date' => $date->format('D'),
                'revenue' => Order::whereDate('created_at', $date)->where('status', 'completed')->sum('total_amount'),
                'orders' => Order::whereDate('created_at', $date)->count(),
            ];
        }

        // Low stock alerts
        $lowStockItems = InventoryItem::lowStock()->take(5)->get();

        // Order status distribution
        $orderStatusDist = Order::select('status', DB::raw('count(*) as count'))
            ->whereDate('created_at', today())
            ->groupBy('status')->get();

        // Payment method breakdown (today)
        $rawPaymentMethods = Payment::join('orders', 'payments.order_id', '=', 'orders.id')
            ->whereDate('orders.created_at', today())
            ->where('payments.status', 'completed')
            ->select('payments.method', DB::raw('SUM(payments.amount) as total'))
            ->groupBy('payments.method')->get()->keyBy('method');

        $paymentMethods = collect([
            ['method' => 'Cash', 'total' => (float) ($rawPaymentMethods->get('cash')->total ?? 0)],
            ['method' => 'Card', 'total' => (float) ($rawPaymentMethods->get('card')->total ?? 0)],
            ['method' => 'Mobile Banking', 'total' => (float) ($rawPaymentMethods->get('mobile_banking')->total ?? 0)],
        ]);

        foreach ($rawPaymentMethods as $key => $data) {
            if (!in_array($key, ['cash', 'card', 'mobile_banking'])) {
                $paymentMethods->push(['method' => ucfirst($key), 'total' => (float) $data->total]);
            }
        }

        return view('dashboard', compact(
            'todaySales', 'todayOrders', 'todayNewCustomers',
            'weeklySales', 'monthlySales',
            'activeTables', 'totalTables', 'availableTables',
            'kitchenPending', 'pendingDeliveries',
            'recentOrders', 'activeOrders',
            'topSellingItems', 'revenueChart',
            'lowStockItems', 'orderStatusDist', 'paymentMethods'
        ));
    }
}
