@extends('admin.layouts.app')

@section('header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.tickets.index') }}" class="text-slate-400 hover:text-white transition-colors">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <h2 class="font-bold text-xl text-white">Ticket #{{ $ticket->id }}</h2>
        @if($ticket->status == 'open')
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 uppercase tracking-wider">Open</span>
        @elseif($ticket->status == 'in_progress')
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase tracking-wider">In Progress</span>
        @elseif($ticket->status == 'resolved')
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-wider">Resolved</span>
        @else
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-500/10 text-slate-400 border border-slate-500/20 uppercase tracking-wider">Closed</span>
        @endif
    </div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Ticket Details -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-xl font-bold text-white mb-2">{{ $ticket->subject }}</h3>
                <div class="flex items-center gap-4 text-sm text-slate-400">
                    <span class="flex items-center gap-1.5"><i class="bi bi-shop"></i> {{ $ticket->tenant->name ?? 'N/A' }}</span>
                    <span class="flex items-center gap-1.5"><i class="bi bi-person"></i> {{ $ticket->user->name ?? 'N/A' }}</span>
                    <span class="flex items-center gap-1.5"><i class="bi bi-clock"></i> {{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                </div>
            </div>
            
            <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="status" class="bg-slate-800 border-slate-700 text-sm rounded-lg text-white focus:ring-blue-500 focus:border-blue-500 block p-2">
                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <button type="submit" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-semibold rounded-lg transition-colors">
                    Update
                </button>
            </form>
        </div>
    </div>

    <!-- Replies -->
    <div class="space-y-4">
        @forelse($ticket->replies as $reply)
            <div class="flex {{ $reply->admin_id ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] {{ $reply->admin_id ? 'bg-blue-600/20 border-blue-500/30' : 'bg-slate-800 border-slate-700' }} border rounded-2xl p-4">
                    <div class="flex items-center justify-between gap-4 mb-2">
                        <span class="font-bold text-sm {{ $reply->admin_id ? 'text-blue-400' : 'text-white' }}">
                            {{ $reply->admin_id ? 'Support Team (Admin)' : ($reply->user->name ?? 'User') }}
                        </span>
                        <span class="text-xs text-slate-400">{{ $reply->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="text-slate-300 text-sm whitespace-pre-wrap">{!! nl2br(e($reply->message)) !!}</div>
                    @if($reply->attachment_url)
                        <div class="mt-3 pt-3 border-t border-slate-700">
                            <a href="{{ $reply->attachment_url }}" target="_blank" class="text-blue-400 text-xs font-semibold flex items-center gap-1">
                                <i class="bi bi-paperclip"></i> View Attachment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-slate-500 py-4 text-sm">
                No replies yet.
            </div>
        @endforelse
    </div>

    <!-- Reply Form -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm mt-8">
        <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-slate-300 mb-2">Your Reply</label>
                <textarea id="message" name="message" rows="4" class="w-full bg-slate-800 border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Type your reply here..." required></textarea>
            </div>
            <div class="mb-4">
                <label for="attachment" class="block text-sm font-medium text-slate-300 mb-2">Attachment (Optional)</label>
                <input type="file" id="attachment" name="attachment" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-700 file:text-white hover:file:bg-slate-600">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2">
                    <i class="bi bi-send-fill"></i> Send Reply
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
