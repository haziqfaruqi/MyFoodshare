<?php

namespace App\Notifications;

use App\Models\FoodMatch;
use App\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PickupCompletedNotification extends Notification implements ShouldQueue
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
            'type' => 'pickup_completed',
            'title' => 'Donation Completed! ✅',
            'message' => "Great news! {$this->match->recipient->name} has successfully picked up your {$this->match->foodListing->food_name} donation.",
            'food_listing_id' => $this->match->foodListing->id,
            'food_name' => $this->match->foodListing->food_name,
            'recipient_name' => $this->match->recipient->name,
            'action_url' => route('restaurant.listings.show', $this->match->foodListing),
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => 'Donation Completed! ✅',
            'body' => "Great news! {$this->match->recipient->name} has successfully picked up your {$this->match->foodListing->food_name} donation.",
            'data' => [
                'type' => 'pickup_completed',
                'food_listing_id' => $this->match->foodListing->id,
                'action_url' => route('restaurant.listings.show', $this->match->foodListing),
            ],
        ];
    }
}