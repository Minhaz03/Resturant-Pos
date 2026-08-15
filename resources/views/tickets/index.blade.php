@extends('layouts.app')

@section('title', 'Support Tickets')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 text-dark fw-bold">Support Tickets</h4>
        <p class="text-muted small mb-0">Manage your support requests and communicate with the support team.</p>
    </div>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> New Ticket
    </a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">TICKET ID</th>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">SUBJECT</th>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">STATUS</th>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">PRIORITY</th>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">LAST UPDATED</th>
                        <th class="border-0 px-4 py-3 text-end text-muted" style="font-weight: 600; font-size: 0.85rem;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td class="px-4 py-3">
                                <span class="fw-bold text-dark">#{{ $ticket->id }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="fw-semibold text-dark">{{ $ticket->subject }}</div>
                                <div class="text-muted small">Opened by {{ $ticket->user->name ?? 'You' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($ticket->status == 'open')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">OPEN</span>
                                @elseif($ticket->status == 'in_progress')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 text-dark">IN PROGRESS</span>
                                @elseif($ticket->status == 'resolved')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">RESOLVED</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">CLOSED</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($ticket->priority == 'high')
                                    <span class="text-danger fw-bold small"><i class="bi bi-arrow-up-circle-fill me-1"></i>High</span>
                                @elseif($ticket->priority == 'medium')
                                    <span class="text-warning fw-bold small"><i class="bi bi-dash-circle-fill me-1"></i>Medium</span>
                                @else
                                    <span class="text-secondary fw-bold small"><i class="bi bi-arrow-down-circle-fill me-1"></i>Low</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-muted small">
                                {{ $ticket->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-4 py-3 text-end">
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary rounded-3">
                                    View Thread
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="mb-3">
                                    <i class="bi bi-envelope-paper" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                                <h6>No support tickets found</h6>
                                <p class="small mb-0">If you need help, feel free to open a new support ticket.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $tickets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
