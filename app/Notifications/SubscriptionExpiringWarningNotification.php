<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringWarningNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $subscription;
    public $days;

    public function __construct($subscription, $days = 3)
    {
        $this->subscription = $subscription;
        $this->days = $days;
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

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Subscription Expiring Soon',
            'message' => 'The subscription for ' . $this->subscription->tenant->name . ' is expiring in ' . $this->days . ' days.',
            'icon' => 'bi bi-clock-history',
            'type' => 'subscription_warning',
            'subscription_id' => $this->subscription->id,
            'url' => route('admin.subscriptions.index')
        ];
    }
}
