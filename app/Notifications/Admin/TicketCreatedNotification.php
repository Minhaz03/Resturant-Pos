<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class TicketCreatedNotification extends Notification
{
    use Queueable;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New Support Ticket: ' . $this->ticket->subject,
            'message' => 'A new ticket has been opened by ' . ($this->ticket->tenant->name ?? 'Unknown Tenant') . '.',
            'ticket_id' => $this->ticket->id,
            'url' => route('admin.tickets.show', $this->ticket->id)
        ];
    }
}
