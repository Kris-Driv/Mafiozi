<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User
     */
    protected $sender;

    /**
     * @var string
     */
    protected $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(?User $sender, string $message)
    {
        $this->sender = $sender;
        $this->message = $message;    
    }

    public function broadcastWith() : array
    {
        return [
            "user" => $this->sender,
            "message" => $this->message
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat');
    }
}
