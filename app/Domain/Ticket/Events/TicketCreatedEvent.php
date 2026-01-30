<?php

namespace App\Domain\Ticket\Events;

use App\Domain\Ticket\Models\Ticket;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

class TicketCreatedEvent implements ShouldBroadcast
{
    use Dispatchable;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->ticket->user_id);
    }
}
