<?php

namespace App\Notifications;

use App\Models\PickupVerification;
use App\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PickupVerifiedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $verification;

    public function __construct(PickupVerification $verification)
    {
        $this->verification = $verification;
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
            'type' => 'pickup_verified',
            'title' => 'Pickup Verified! ✅',
            'message' => "{$this->verification->recipient->name} has scanned the QR code and verified pickup of {$this->verification->foodListing->food_name}.",
            'food_listing_id' => $this->verification->foodListing->id,
            'food_name' => $this->verification->foodListing->food_name,
            'recipient_name' => $this->verification->recipient->name,
            'verification_code' => $this->verification->verification_code,
            'scanned_at' => $this->verification->scanned_at,
            'action_url' => route('restaurant.listings.show', $this->verification->foodListing),
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => 'Pickup Verified! ✅',
            'body' => "{$this->verification->recipient->name} has scanned the QR code and verified pickup of {$this->verification->foodListing->food_name}.",
            'data' => [
                'type' => 'pickup_verified',
                'food_listing_id' => $this->verification->foodListing->id,
                'verification_code' => $this->verification->verification_code,
                'action_url' => route('restaurant.listings.show', $this->verification->foodListing),
            ],
        ];
    }
}