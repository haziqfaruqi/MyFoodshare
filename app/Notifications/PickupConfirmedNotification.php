<?php

namespace App\Notifications;

use App\Models\FoodMatch;
use App\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PickupConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $match;

    public function __construct(FoodMatch $match)
    {
        $this->match = $match;
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        
        if ($notifiable->fcm_token && $notifiable->push_notifications_enabled) {
            $channels[] = FcmChannel::class;
        }
        
        return $channels;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'pickup_confirmed',
            'title' => 'Pickup Confirmed! 🚗',
            'message' => "Your pickup for {$this->match->foodListing->food_name} has been confirmed. Please proceed to the pickup location.",
            'food_listing_id' => $this->match->foodListing->id,
            'food_name' => $this->match->foodListing->food_name,
            'pickup_location' => $this->match->foodListing->pickup_location,
            'action_url' => route('recipient.browse.show', $this->match->foodListing),
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => 'Pickup Confirmed! 🚗',
            'body' => "Your pickup for {$this->match->foodListing->food_name} has been confirmed. Please proceed to {$this->match->foodListing->pickup_location}.",
            'data' => [
                'type' => 'pickup_confirmed',
                'food_listing_id' => $this->match->foodListing->id,
                'action_url' => route('recipient.browse.show', $this->match->foodListing),
            ],
        ];
    }
}