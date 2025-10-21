<?php

namespace App\Notifications;

use App\Models\FoodMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class InterestExpressedNotification extends Notification // Removed ShouldQueue for testing
{
    // use Queueable; // Commented out for sync testing

    protected $foodMatch;

    public function __construct(FoodMatch $foodMatch)
    {
        $this->foodMatch = $foodMatch;
    }

    public function via(object $notifiable): array
    {
        // Using database and broadcast (Pusher) for real-time notifications
        // FCM disabled until Firebase is properly configured
        return ['database', 'broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $data = [
            'type' => 'interest_expressed',
            'title' => 'Someone is Interested in Your Food Donation!',
            'message' => "{$this->foodMatch->recipient->name} has expressed interest in your {$this->foodMatch->listing->food_name}",
            'food_match_id' => $this->foodMatch->id,
            'action_url' => route('restaurant.listings.show', $this->foodMatch->listing),
        ];

        \Log::info('Broadcasting notification', [
            'notifiable_id' => $notifiable->id,
            'notification_type' => 'interest_expressed',
            'channel' => 'App.Models.User.' . $notifiable->id,
            'data' => $data
        ]);

        return new BroadcastMessage($data);
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