<?php

namespace App\Notifications;

use App\Models\FoodMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFoodMatchNotification extends Notification
{
    use Queueable;

    public $match;
    public $isForDonor;

    public function __construct(FoodMatch $match, bool $isForDonor = false)
    {
        $this->match = $match;
        $this->isForDonor = $isForDonor;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        if ($this->isForDonor) {
            return (new MailMessage)
                ->subject('New Interest in Your Food Listing')
                ->line("A recipient has expressed interest in your food listing: {$this->match->foodListing->food_name}")
                ->line("Distance: {$this->match->distance} km")
                ->action('View Match Details', route('restaurant.listings.show', $this->match->foodListing))
                ->line('You can approve or decline this match from your dashboard.');
        } else {
            return (new MailMessage)
                ->subject('New Food Available Near You')
                ->line("A new food listing is available near you: {$this->match->foodListing->food_name}")
                ->line("Donor: {$this->match->foodListing->user->name}")
                ->line("Distance: {$this->match->distance} km")
                ->action('View Listing', route('recipient.browse.show', $this->match->foodListing))
                ->line('Express your interest to claim this food donation.');
        }
    }

    public function toArray(object $notifiable): array
    {
        if ($this->isForDonor) {
            return [
                'type' => 'new_match_interest',
                'match_id' => $this->match->id,
                'listing_id' => $this->match->food_listing_id,
                'recipient_name' => $this->match->recipient->name,
                'food_name' => $this->match->foodListing->food_name,
                'distance' => $this->match->distance,
                'message' => "New interest in your listing: {$this->match->foodListing->food_name}",
            ];
        } else {
            return [
                'type' => 'new_food_available',
                'match_id' => $this->match->id,
                'listing_id' => $this->match->food_listing_id,
                'donor_name' => $this->match->foodListing->user->name,
                'food_name' => $this->match->foodListing->food_name,
                'distance' => $this->match->distance,
                'message' => "New food available: {$this->match->foodListing->food_name}",
            ];
        }
    }
}
