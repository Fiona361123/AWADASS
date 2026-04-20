<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message) {}

    //only allow authenticated users who are part of the conversation to listen to the event
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
        ];
    }

    //what frontend can see
    public function broadcastWith(): array
    {
        return [
            'id'          => $this->message->id,
            'body'        => $this->message->body,
            'sender_id'   => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'created_at'  => $this->message->created_at->toISOString(),
        ];
    }
}