<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PropertyCalendarChanged implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public string $reason = 'updated')
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('property-calendar');
    }

    public function broadcastAs(): string
    {
        return 'property-calendar.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'reason' => $this->reason,
            'changed_at' => now()->toIso8601String(),
        ];
    }
}
