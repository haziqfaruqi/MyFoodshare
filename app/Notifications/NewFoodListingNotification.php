<?php

namespace App\Notifications;

use App\Models\FoodListing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class NewFoodListingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $foodListing;

    public function __construct(FoodListing $foodListing)
    {
        $this->foodListing = $foodListing;
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
            'type' => 'new_food_listing',
            'title' => 'New Food Available Near You!',
            'message' => "{$this->foodListing->food_name} is available for pickup at {$this->foodListing->donor->restaurant_name ?? $this->foodListing->donor->name}",
            'food_listing_id' => $this->foodListing->id,
            'donor_name' => $this->foodListing->donor->restaurant_name ?? $this->foodListing->donor->name,
            'food_name' => $this->foodListing->food_name,
            'quantity' => $this->foodListing->quantity . ' ' . $this->foodListing->unit,
            'expiry_date' => $this->foodListing->expiry_date->format('M d, Y'),
            'action_url' => route('recipient.browse.show', $this->foodListing),
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => 'New Food Available Near You! 🍽️',
            'body' => "{$this->foodListing->food_name} ({$this->foodListing->quantity} {$this->foodListing->unit}) from {$this->foodListing->donor->restaurant_name ?? $this->foodListing->donor->name}",
            'data' => [
                'type' => 'new_food_listing',
                'food_listing_id' => $this->foodListing->id,
                'action_url' => route('recipient.browse.show', $this->foodListing),
            ],
        ];
    }
}
