<?php

namespace App\Domain\Ticket\Events;

use App\Domain\Ticket\Models\Ticket;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

class TicketCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        info(__METHOD__);
        $this->ticket = $ticket;
    }

    // Добавьте это свойство
    public function broadcastAs(): string
    {
        return 'ticket.created';
    }
    public function broadcastOn(): PrivateChannel
    {
        info(__METHOD__);
        return new PrivateChannel('user.' . $this->ticket->user_id);
    }
}
