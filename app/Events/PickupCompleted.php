<?php

namespace App\Events;

use App\Models\PickupVerification;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PickupCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $verification;
    public $recipient;

    /**
     * Create a new event instance.
     */
    public function __construct(PickupVerification $verification, User $recipient)
    {
        $this->verification = $verification;
        $this->recipient = $recipient;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('restaurant.' . $this->verification->donor_id),
            new PrivateChannel('admin.dashboard'),
            new PrivateChannel('pickup.' . $this->verification->id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'verification_id' => $this->verification->id,
            'food_listing' => [
                'id' => $this->verification->foodListing->id,
                'food_name' => $this->verification->foodListing->food_name,
                'quantity' => $this->verification->foodListing->quantity . ' ' . $this->verification->foodListing->unit,
                'status' => 'completed',
            ],
            'recipient' => [
                'id' => $this->recipient->id,
                'name' => $this->recipient->name,
                'email' => $this->recipient->email,
            ],
            'restaurant' => [
                'id' => $this->verification->donor->id,
                'name' => $this->verification->donor->name,
            ],
            'completed_at' => $this->verification->pickup_completed_at->toISOString(),
            'quality_rating' => $this->verification->quality_rating,
            'quality_confirmed' => $this->verification->quality_confirmed,
            'status' => 'pickup_completed',
            'message' => $this->recipient->name . ' has completed pickup of ' . $this->verification->foodListing->food_name,
        ];
    }

    /**
     * Get the broadcast event name.
     */
    public function broadcastAs(): string
    {
        return 'pickup-completed';
    }
}