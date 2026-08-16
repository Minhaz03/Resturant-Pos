@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-light border shadow-sm text-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div class="flex-grow-1">
        <div class="d-flex align-items-center gap-3">
            <h4 class="mb-0 text-dark fw-bold">{{ $ticket->subject }}</h4>
            @if($ticket->status == 'open')
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">OPEN</span>
            @elseif($ticket->status == 'in_progress')
                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 text-dark">IN PROGRESS</span>
            @elseif($ticket->status == 'resolved')
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">RESOLVED</span>
            @else
                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">CLOSED</span>
            @endif
        </div>
        <div class="d-flex align-items-center gap-2 mt-1">
            <p class="text-muted small mb-0">
                Ticket #{{ $ticket->id }} &bull; Opened on {{ $ticket->created_at->format('M d, Y') }}
            </p>
        </div>
    </div>
    <div>
        @if($ticket->status != 'closed')
            <form id="closeTicketForm" action="{{ route('tickets.status', $ticket) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="closed">
                <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1 shadow-sm px-3 py-1.5 rounded-3" onclick="confirmCloseTicket()">
                    <i class="bi bi-x-circle"></i> Close Ticket
                </button>
            </form>
        @else
            <form id="reopenTicketForm" action="{{ route('tickets.status', $ticket) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="open">
                <button type="button" class="btn btn-sm btn-outline-success d-flex align-items-center gap-1 shadow-sm px-3 py-1.5 rounded-3" onclick="confirmReopenTicket()">
                    <i class="bi bi-arrow-counterclockwise"></i> Reopen Ticket
                </button>
            </form>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        
        <!-- Thread -->
        <div class="d-flex flex-column gap-4 mb-4">
            @forelse($ticket->replies as $reply)
                @if($reply->admin_id)
                    <!-- Admin Reply -->
                    <div class="d-flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                <i class="bi bi-headset fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="card shadow-sm border-0 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-10">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 fw-bold text-primary">Support Team</h6>
                                        <span class="small text-muted">{{ $reply->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    <div class="text-dark whitespace-pre-wrap" style="line-height: 1.6;">{!! nl2br(e($reply->message)) !!}</div>
                                    @if($reply->attachment_path)
                                        <div class="mt-3 pt-3 border-top border-primary border-opacity-25">
                                            <a href="{{ Storage::url($reply->attachment_path) }}" target="_blank" class="text-primary text-decoration-none small fw-semibold d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-paperclip fs-6"></i> View Attachment
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- User Reply -->
                    <div class="d-flex gap-3 flex-row-reverse">
                        <div class="flex-shrink-0">
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px; font-weight: bold;">
                                {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-grow-1 text-end">
                            <div class="card shadow-sm border-0 rounded-4 bg-white d-inline-block text-start" style="max-width: 90%;">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2 gap-4">
                                        <h6 class="mb-0 fw-bold text-dark">{{ $reply->user->name ?? 'You' }}</h6>
                                        <span class="small text-muted">{{ $reply->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    <div class="text-secondary whitespace-pre-wrap" style="line-height: 1.6;">{!! nl2br(e($reply->message)) !!}</div>
                                    @if($reply->attachment_url)
                                        <div class="mt-2">
                                            <a href="{{ $reply->attachment_url }}" target="_blank" class="text-primary text-decoration-none small fw-semibold d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-paperclip fs-6"></i> View Attachment
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-5 text-muted">No messages found.</div>
            @endforelse
        </div>

        <!-- Reply Box -->
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <form action="{{ route('tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="message" class="form-label text-muted fw-semibold small text-uppercase">Add a Reply</label>
                        <textarea class="form-control bg-light @error('message') is-invalid @enderror" id="message" name="message" rows="4" placeholder="Type your message here..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label text-muted fw-semibold small text-uppercase">Attachment (Optional)</label>
                        <input class="form-control bg-light @error('attachment') is-invalid @enderror" type="file" id="attachment" name="attachment">
                        <div class="form-text">Max file size: 5MB.</div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold d-flex align-items-center gap-2">
                            <i class="bi bi-send"></i> Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Sidebar details -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom px-4 py-3">
                <h6 class="mb-0 fw-bold text-secondary">Ticket Details</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div class="text-muted small fw-semibold text-uppercase mb-1">Status</div>
                    @if($ticket->status == 'open')
                        <span class="badge bg-primary px-2 py-1">Open</span>
                    @elseif($ticket->status == 'in_progress')
                        <span class="badge bg-warning text-dark px-2 py-1">In Progress</span>
                    @elseif($ticket->status == 'resolved')
                        <span class="badge bg-success px-2 py-1">Resolved</span>
                    @else
                        <span class="badge bg-secondary px-2 py-1">Closed</span>
                    @endif
                </div>
                <div class="mb-3">
                    <div class="text-muted small fw-semibold text-uppercase mb-1">Priority</div>
                    @if($ticket->priority == 'high')
                        <span class="text-danger fw-bold"><i class="bi bi-arrow-up-circle-fill me-1"></i>High</span>
                    @elseif($ticket->priority == 'medium')
                        <span class="text-warning fw-bold"><i class="bi bi-dash-circle-fill me-1"></i>Medium</span>
                    @else
                        <span class="text-secondary fw-bold"><i class="bi bi-arrow-down-circle-fill me-1"></i>Low</span>
                    @endif
                </div>
                <div class="mb-3">
                    <div class="text-muted small fw-semibold text-uppercase mb-1">Created By</div>
                    <div class="text-dark fw-medium">{{ $ticket->user->name ?? 'N/A' }}</div>
                </div>
                <div class="mb-0">
                    <div class="text-muted small fw-semibold text-uppercase mb-1">Last Update</div>
                    <div class="text-dark fw-medium">{{ $ticket->updated_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmCloseTicket() {
    Swal.fire({
        title: 'Close Support Ticket?',
        text: 'Are you sure you want to mark this ticket as closed?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-x-circle me-1"></i> Yes, Close Ticket',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('closeTicketForm').submit();
        }
    });
}

function confirmReopenTicket() {
    Swal.fire({
        title: 'Reopen Support Ticket?',
        text: 'Do you want to reopen this ticket and resume the support conversation?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-arrow-counterclockwise me-1"></i> Yes, Reopen Ticket',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reopenTicketForm').submit();
        }
    });
}
</script>
@endpush
