<?php

namespace App\Events;

use App\Models\FoodMatch;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $foodMatch;

    public function __construct(FoodMatch $foodMatch)
    {
        $this->foodMatch = $foodMatch;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->foodMatch->recipient_id),
            new PrivateChannel('user.' . $this->foodMatch->listing->user_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'match_id' => $this->foodMatch->id,
            'status' => $this->foodMatch->status,
            'scheduled_at' => $this->foodMatch->pickup_scheduled_at?->format('Y-m-d H:i:s'),
            'food_name' => $this->foodMatch->listing->food_name,
            'message' => $this->getStatusMessage(),
        ];
    }

    private function getStatusMessage(): string
    {
        return match($this->foodMatch->status) {
            'pending' => 'Interest expressed',
            'approved' => 'Pickup approved',
            'scheduled' => 'Pickup scheduled',
            'completed' => 'Pickup completed',
            'rejected' => 'Match rejected',
            'cancelled' => 'Match cancelled',
            default => 'Status updated',
        };
    }
}
