<?php

namespace App\Events;

use App\Models\Conversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AddNewGroup implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $converstion;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($converstion)
    {
        $this->converstion = $converstion;
    }

    public function broadcastWith()
    {

        $userIds = $this->converstion->users->pluck('id')->toArray();
        return [
            'available_users' => $userIds,
            'converstion'=>$this->converstion
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('AddNewGroup');
    }
}
