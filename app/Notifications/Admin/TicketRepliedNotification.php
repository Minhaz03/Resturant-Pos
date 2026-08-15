<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class TicketRepliedNotification extends Notification
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
            'title' => 'New Reply to Ticket: ' . $this->ticket->subject,
            'message' => 'The tenant ' . ($this->ticket->tenant->name ?? 'Unknown') . ' replied to the ticket.',
            'ticket_id' => $this->ticket->id,
            'url' => route('admin.tickets.show', $this->ticket->id)
        ];
    }
}
