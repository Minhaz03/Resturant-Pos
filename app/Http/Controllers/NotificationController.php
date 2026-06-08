<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = AppNotification::where('user_id', auth()->id())
            ->latest()->paginate(20);
        $unreadCount = AppNotification::where('user_id', auth()->id())->where('is_read', false)->count();
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function unreadCount()
    {
        $count = AppNotification::where('user_id', auth()->id())->unread()->count();
        return response()->json(['count' => $count]);
    }

    public function markAllRead()
    {
        AppNotification::where('user_id', auth()->id())->where('is_read', false)->update(['is_read' => true, 'read_at' => now()]);
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(AppNotification $notification)
    {
        $notification->delete();
        return back()->with('success', 'Notification deleted.');
    }
}
