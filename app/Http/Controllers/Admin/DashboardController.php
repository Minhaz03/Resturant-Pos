<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tenantCount = \App\Models\Tenant::count();
        $activeSubscriptions = \App\Models\Subscription::where('status', 'active')->count();
        
        // Calculate current month's revenue
        $monthlyRevenue = \App\Models\Subscription::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->whereIn('status', ['active', 'expired'])
                            ->sum('amount');

        $recentTenants = \App\Models\Tenant::latest()->take(5)->get();
        
        $notifications = auth('admin')->user()->notifications()->take(5)->get();
        $unreadCount = auth('admin')->user()->unreadNotifications()->count();

        return view('admin.dashboard', compact('tenantCount', 'activeSubscriptions', 'monthlyRevenue', 'recentTenants', 'notifications', 'unreadCount'));
    }

    public function notifications()
    {
        $notifications = auth('admin')->user()->notifications()->paginate(20);
        $unreadCount = auth('admin')->user()->unreadNotifications()->count();
        
        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markNotificationsAsRead()
    {
        auth('admin')->user()->unreadNotifications->markAsRead();
        return back();
    }
}
