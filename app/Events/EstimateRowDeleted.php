<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EstimateRowDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int    $projectId,
        public string $uniqueIdentifier
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('estimate.' . $this->projectId);
    }

    public function broadcastAs(): string
    {
        return 'row.deleted';
    }
}
