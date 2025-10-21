<?php

namespace App\Notifications;

use App\Models\FoodMatch;
use App\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class PickupConfirmedNotification extends Notification // Removed ShouldQueue
{
    // use Queueable; // Commented for sync notifications

    protected $match;

    public function __construct(FoodMatch $match)
    {
        $this->match = $match;
    }

    public function via(object $notifiable): array
    {
        // Using database and broadcast (Pusher) for real-time notifications
        return ['database', 'broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'pickup_confirmed',
            'title' => 'Pickup Confirmed!',
            'message' => "Your pickup for {$this->match->foodListing->food_name} has been confirmed. Please proceed to the pickup location.",
            'food_listing_id' => $this->match->foodListing->id,
            'action_url' => route('recipient.browse.show', $this->match->foodListing),
        ]);
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