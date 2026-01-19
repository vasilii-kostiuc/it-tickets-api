<?php

namespace App\Domain\Ticket\Events;

use App\Domain\Ticket\Models\Ticket;
use Illuminate\Foundation\Events\Dispatchable;

class TicketCreatedEvent
{
    use Dispatchable;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }
}
