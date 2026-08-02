<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\Admin;
use App\Models\AppNotification;
use App\Notifications\SubscriptionExpiredNotification;
use App\Notifications\SubscriptionExpiringWarningNotification;
use Illuminate\Support\Facades\Notification;

class CheckSubscriptionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired subscriptions and send warnings for expiring soon subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Find subscriptions that expired today (or have passed their end date and are still active)
        $expiredSubscriptions = Subscription::where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->get();

        foreach ($expiredSubscriptions as $sub) {
            $sub->update(['status' => 'expired']);

            // Notify Super Admin
            Notification::send(Admin::all(), new SubscriptionExpiredNotification($sub));

            // Notify Owner
            $owner = $sub->tenant->users()->first();
            if ($owner) {
                AppNotification::create([
                    'user_id' => $owner->id,
                    'type' => 'subscription_expired',
                    'title' => 'Subscription Expired',
                    'message' => 'Your subscription for ' . $sub->plan->name . ' has expired.',
                    'icon' => 'bi bi-exclamation-triangle',
                    'color' => 'danger',
                    'action_url' => route('dashboard.billing'),
                ]);
            }
        }

        $this->info("Processed {$expiredSubscriptions->count()} expired subscriptions.");

        // 2. Find subscriptions expiring in exactly 3 days
        $expiringSubscriptions = Subscription::where('status', 'active')
            ->whereNotNull('ends_at')
            ->whereDate('ends_at', '=', now()->addDays(3)->toDateString())
            ->get();

        foreach ($expiringSubscriptions as $sub) {
            // Notify Super Admin
            Notification::send(Admin::all(), new SubscriptionExpiringWarningNotification($sub, 3));

            // Notify Owner
            $owner = $sub->tenant->users()->first();
            if ($owner) {
                AppNotification::create([
                    'user_id' => $owner->id,
                    'type' => 'subscription_warning',
                    'title' => 'Subscription Expiring Soon',
                    'message' => 'Your subscription for ' . $sub->plan->name . ' is expiring in 3 days.',
                    'icon' => 'bi bi-clock-history',
                    'color' => 'warning',
                    'action_url' => route('dashboard.billing'),
                ]);
            }
        }

        $this->info("Processed {$expiringSubscriptions->count()} expiring subscriptions.");
    }
}
