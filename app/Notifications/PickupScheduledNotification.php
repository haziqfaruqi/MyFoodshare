<?php

namespace App\Notifications;

use App\Models\FoodMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PickupScheduledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $foodMatch;

    public function __construct(FoodMatch $foodMatch)
    {
        $this->foodMatch = $foodMatch;
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->push_notifications_enabled) {
            $channels[] = 'fcm';
        }

        return $channels;
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'pickup_scheduled',
            'title' => 'Pickup Scheduled! 📅',
            'message' => "Your pickup for {$this->foodMatch->listing->food_name} has been scheduled for " . $this->foodMatch->pickup_scheduled_at->format('M d, Y \a\t h:i A'),
            'food_match_id' => $this->foodMatch->id,
            'food_listing_id' => $this->foodMatch->listing->id,
            'donor_name' => $this->foodMatch->listing->user->name,
            'food_name' => $this->foodMatch->listing->food_name,
            'scheduled_at' => $this->foodMatch->pickup_scheduled_at->format('Y-m-d H:i:s'),
            'pickup_location' => $this->foodMatch->listing->pickup_location,
            'action_url' => route('recipient.matches.index'),
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => 'Pickup Scheduled! 📅',
            'body' => "Pickup for {$this->foodMatch->listing->food_name} scheduled on " . $this->foodMatch->pickup_scheduled_at->format('M d \a\t h:i A'),
            'data' => [
                'type' => 'pickup_scheduled',
                'food_match_id' => $this->foodMatch->id,
                'action_url' => route('recipient.matches.index'),
            ],
        ];
    }
}
