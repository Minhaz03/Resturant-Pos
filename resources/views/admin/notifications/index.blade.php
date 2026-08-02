@extends('admin.layouts.app')

@section('header', 'Notifications')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-white">All Notifications</h2>
        <p class="text-gray-400 mt-1">You have <span class="bg-red-500 text-white px-2 py-0.5 rounded-full text-xs font-bold">{{ $unreadCount }}</span> unread notifications.</p>
    </div>
    @if($unreadCount > 0)
    <form action="{{ route('admin.notifications.read') }}" method="POST">
        @csrf
        <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-gray-600">
            <i class="bi bi-check2-all me-2"></i>Mark All Read
        </button>
    </form>
    @endif
</div>

<div class="bg-gray-800 rounded-2xl shadow-lg border border-gray-700 overflow-hidden">
    <div class="divide-y divide-gray-700/50">
        @forelse($notifications as $notification)
            <a href="{{ $notification->data['url'] ?? '#' }}" class="block p-5 hover:bg-gray-700/30 transition-colors {{ is_null($notification->read_at) ? 'bg-gray-700/10' : '' }}">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ is_null($notification->read_at) ? 'bg-blue-500/10 text-blue-400' : 'bg-gray-700 text-gray-500' }}">
                            <i class="{{ $notification->data['icon'] ?? 'bi bi-bell' }} text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-base font-medium {{ is_null($notification->read_at) ? 'text-white' : 'text-gray-300' }}">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </p>
                            <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-400 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                    </div>
                    @if(is_null($notification->read_at))
                        <div class="ml-4 flex-shrink-0 mt-3">
                            <div class="w-2.5 h-2.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.8)]"></div>
                        </div>
                    @endif
                </div>
            </a>
        @empty
            <div class="p-12 text-center text-gray-500 flex flex-col items-center">
                <i class="bi bi-bell-slash text-6xl mb-4 opacity-20"></i>
                <h3 class="text-lg font-medium text-gray-400">No Notifications</h3>
                <p class="mt-2">You're all caught up! No recent notifications found.</p>
            </div>
        @endforelse
    </div>
    
    @if($notifications->hasPages())
    <div class="p-4 border-t border-gray-700 bg-gray-800/50">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection
