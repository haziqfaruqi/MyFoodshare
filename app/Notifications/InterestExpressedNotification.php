<?php

namespace App\Notifications;

use App\Models\FoodMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterestExpressedNotification extends Notification implements ShouldQueue
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
            'type' => 'interest_expressed',
            'title' => 'Someone is Interested in Your Food Donation! 👋',
            'message' => "{$this->foodMatch->recipient->name} has expressed interest in your {$this->foodMatch->listing->food_name}",
            'food_match_id' => $this->foodMatch->id,
            'food_listing_id' => $this->foodMatch->listing->id,
            'recipient_name' => $this->foodMatch->recipient->name,
            'recipient_organization' => $this->foodMatch->recipient->organization_name,
            'food_name' => $this->foodMatch->listing->food_name,
            'distance' => $this->foodMatch->distance ? round($this->foodMatch->distance, 1) . 'km' : null,
            'action_url' => route('restaurant.listings.show', $this->foodMatch->listing),
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => 'New Interest in Your Donation! 🤝',
            'body' => "{$this->foodMatch->recipient->name} wants your {$this->foodMatch->listing->food_name}. Tap to view details.",
            'data' => [
                'type' => 'interest_expressed',
                'food_match_id' => $this->foodMatch->id,
                'action_url' => route('restaurant.listings.show', $this->foodMatch->listing),
            ],
        ];
    }
}