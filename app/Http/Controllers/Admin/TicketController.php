<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\AppNotification;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['tenant', 'user'])->latest()->paginate(15);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['tenant', 'user', 'replies.user', 'replies.admin']);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets', 'public');
        }

        $ticket->replies()->create([
            'admin_id' => auth('admin')->id(),
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
        ]);

        if ($ticket->status == 'closed' || $ticket->status == 'resolved') {
            $ticket->update(['status' => 'open']);
        }

        AppNotification::create([
            'user_id' => $ticket->user_id,
            'type' => 'ticket_reply',
            'title' => 'New Reply on Ticket #' . $ticket->id,
            'message' => 'The support team has replied to your ticket: ' . $ticket->subject,
            'icon' => 'bi bi-headset',
            'color' => 'primary',
            'action_url' => route('tickets.show', $ticket->id)
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,closed,resolved',
        ]);

        $ticket->update(['status' => $request->status]);

        AppNotification::create([
            'user_id' => $ticket->user_id,
            'type' => 'ticket_status',
            'title' => 'Ticket Status Updated',
            'message' => 'Your ticket "' . $ticket->subject . '" is now ' . $ticket->status . '.',
            'icon' => 'bi bi-info-circle',
            'color' => 'info',
            'action_url' => route('tickets.show', $ticket->id)
        ]);

        return back()->with('success', 'Ticket status updated.');
    }
}
