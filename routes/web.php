<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('central.landing');
});

Route::post('/register-tenant', [\App\Http\Controllers\Central\TenantRegistrationController::class, 'store'])->name('tenant.register');

Route::middleware(['tenant', 'auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Menu Items
    Route::resource('menu', MenuItemController::class);
    Route::patch('menu/{menuItem}/availability', [MenuItemController::class, 'toggleAvailability'])->name('menu.toggle-availability');

    // Tables
    Route::resource('tables', TableController::class);
    Route::patch('tables/{table}/status', [TableController::class, 'updateStatus'])->name('tables.update-status');

    // Reservations
    Route::resource('reservations', ReservationController::class);

    // Orders
    Route::resource('orders', OrderController::class)->except(['edit', 'update']);
    Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{order}/settle', [OrderController::class, 'settlePayment'])->name('orders.settle');

    // POS
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/process', [POSController::class, 'processOrder'])->name('pos.process');
    Route::get('/pos/search-product', [POSController::class, 'searchProduct'])->name('pos.search-product');
    Route::post('/pos/validate-coupon', [POSController::class, 'validateCoupon'])->name('pos.validate-coupon');
    Route::get('/pos/active-reservations', [POSController::class, 'getActiveReservations'])->name('pos.active-reservations');

    // Kitchen (KDS)
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
    Route::patch('/kitchen/{kitchenOrder}/status', [KitchenController::class, 'updateStatus'])->name('kitchen.update-status');
    Route::patch('/kitchen/order/{order}/serve', [KitchenController::class, 'serveOrder'])->name('kitchen.serve-order');
    Route::patch('/kitchen/order/{order}/status', [KitchenController::class, 'updateOrderStatus'])->name('kitchen.update-order-status');
    Route::get('/kitchen/new-orders', [KitchenController::class, 'getNewOrders'])->name('kitchen.new-orders');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');

    // Employees & Attendance
    Route::resource('employees', EmployeeController::class);
    Route::get('/attendance', [EmployeeController::class, 'attendance'])->name('employees.attendance');
    Route::post('/attendance', [EmployeeController::class, 'markAttendance'])->name('employees.mark-attendance');
    Route::get('/employees/{employee}/attendance', [EmployeeController::class, 'employeeAttendance'])->name('employees.employee-attendance');
    Route::post('/employees/{employee}/mark-attendance', [EmployeeController::class, 'markEmployeeAttendance'])->name('employees.mark-attendance-individual');

    // Inventory
    Route::resource('inventory', InventoryController::class);
    Route::post('/inventory/{inventoryItem}/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Purchase Orders
    Route::resource('purchases', PurchaseOrderController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/purchases/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchases.receive');

    // Delivery
    Route::get('/delivery', [DeliveryController::class, 'index'])->name('delivery.index');
    Route::post('/delivery/{delivery}/assign', [DeliveryController::class, 'assign'])->name('delivery.assign');
    Route::patch('/delivery/{delivery}/status', [DeliveryController::class, 'updateStatus'])->name('delivery.update-status');

    // Coupons
    Route::resource('coupons', CouponController::class);

    // Reports
    Route::get('/reports/sales', [ReportController::class, 'salesIndex'])->name('reports.sales');
    Route::get('/reports/sales/data', [ReportController::class, 'salesData'])->name('reports.sales.data');
    Route::get('/reports/sales/pdf', [ReportController::class, 'exportSalesPdf'])->name('reports.sales.pdf');
    Route::get('/reports/sales/csv', [ReportController::class, 'exportSalesCsv'])->name('reports.sales.csv');
    Route::get('/reports/sales/excel', [ReportController::class, 'exportSalesExcel'])->name('reports.sales.excel');
    Route::get('/reports/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
    Route::get('/reports/inventory/csv', [ReportController::class, 'exportInventoryCsv'])->name('reports.inventory.csv');
    Route::get('/reports/customers', [ReportController::class, 'customerReport'])->name('reports.customers');
    Route::get('/reports/tax', [ReportController::class, 'taxReport'])->name('reports.tax');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::put('/settings/hours', [SettingController::class, 'updateBusinessHours'])->name('settings.hours');

    // Users (Admin)
    Route::resource('users', UserController::class)->except(['show']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

require __DIR__ . '/auth.php';
