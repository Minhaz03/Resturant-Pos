<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\AppNotification;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('tenant_id', auth()->user()->tenant_id)->latest()->paginate(15);
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $ticket = Ticket::create([
            'tenant_id' => auth()->user()->tenant_id,
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets', 'public');
        }

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
        ]);

        // Notify all admins
        $admins = \App\Models\Admin::all();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\Admin\TicketCreatedNotification($ticket));
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->tenant_id != auth()->user()->tenant_id) abort(403);
        $ticket->load(['user', 'replies.user', 'replies.admin']);
        return view('tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->tenant_id != auth()->user()->tenant_id) abort(403);

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets', 'public');
        }

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
        ]);

        if ($ticket->status == 'resolved' || $ticket->status == 'closed') {
            $ticket->update(['status' => 'open']);
        }

        // Notify all admins
        $admins = \App\Models\Admin::all();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\Admin\TicketRepliedNotification($ticket));
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        if ($ticket->tenant_id != auth()->user()->tenant_id) abort(403);

        $request->validate([
            'status' => 'required|in:open,closed',
        ]);

        // Tenants can only toggle between open and closed (or reopen a resolved ticket)
        $ticket->update(['status' => $request->status]);

        return back()->with('success', 'Ticket status updated successfully.');
    }
}
