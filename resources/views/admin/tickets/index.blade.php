@extends('admin.layouts.app')

@section('header')
    <h2 class="font-bold text-xl text-white">Support Tickets</h2>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center bg-slate-900/50">
            <h3 class="text-lg font-bold text-white">All Tickets</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 text-slate-400 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold">ID</th>
                        <th class="p-4 font-semibold">Tenant</th>
                        <th class="p-4 font-semibold">Subject</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold">Priority</th>
                        <th class="p-4 font-semibold">Created</th>
                        <th class="p-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-slate-800/20 transition-colors">
                        <td class="p-4 text-sm text-slate-300 font-medium">#{{ $ticket->id }}</td>
                        <td class="p-4 text-sm text-slate-300">
                            {{ $ticket->tenant->name ?? 'N/A' }}
                            <div class="text-xs text-slate-500">{{ $ticket->user->name ?? 'N/A' }}</div>
                        </td>
                        <td class="p-4 text-sm font-semibold text-white">{{ $ticket->subject }}</td>
                        <td class="p-4">
                            @if($ticket->status == 'open')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 uppercase tracking-wider">Open</span>
                            @elseif($ticket->status == 'in_progress')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase tracking-wider">In Progress</span>
                            @elseif($ticket->status == 'resolved')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-wider">Resolved</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-500/10 text-slate-400 border border-slate-500/20 uppercase tracking-wider">Closed</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($ticket->priority == 'high')
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-rose-500/10 text-rose-400">High</span>
                            @elseif($ticket->priority == 'medium')
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-500/10 text-amber-400">Medium</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-500/10 text-slate-400">Low</span>
                            @endif
                        </td>
                        <td class="p-4 text-sm text-slate-400">{{ $ticket->created_at->format('M d, Y h:i A') }}</td>
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500 text-sm">
                            No tickets found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-800">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection
