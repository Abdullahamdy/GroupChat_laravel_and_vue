<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BlockFriend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
        public $userblocked;
        public $reciver_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($reciver_id,$userblocked = null)
    {
        $this->userblocked = $userblocked ;
        $this->reciver_id = $reciver_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('PrivatesendBlock.'.$this->reciver_id);
    }
}
