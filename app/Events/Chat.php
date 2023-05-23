<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Chat implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $issue_id;

    public $message;

    public $sender_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($issue_id,$message,$sender_id)
    {
        //innitalizes the global variables
        $this->issue_id=$issue_id;

        $this->message=$message;

        $this->sender_id=$sender_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('Chat.'.$this->issue_id);
    }

    public function broadcastWith()
    {
            return [
                'message' => $this->message,
                'sender_id' => $this->sender_id
            ];
    }

     /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'new-message';
    }

}
