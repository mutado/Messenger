<?php
  
namespace App\Events;
  
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Message;  

use Illuminate\Support\Facades\Auth; 

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
  
    private $message;
    private $channelId;
  
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
        $this->channelId = $message->channelId;
    }
  
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel("channel.{$this->channelId}");
    }
  
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'NewMessage';
    }
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastWith()
    {
        return ['message'=>$this->message];
    }
}