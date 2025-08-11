<?php

namespace App\Events;

use App\Models\TodoItem;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TodoItemCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $item;

    public function __construct(TodoItem $item)
    {
        $this->item = $item;
        \Log::info('Broadcasting TodoItemCreated Event', ['item' => $item]);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('todos.' . $this->item->todo_id),
        ];
    }
}
